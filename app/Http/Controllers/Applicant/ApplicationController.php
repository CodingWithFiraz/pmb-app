<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPeriod;
use App\Models\Application;
use App\Models\StudyProgram;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function __construct(protected ApplicationService $service) {}

    public function create()
    {
        $activePeriod = AdmissionPeriod::getActive();

        if (! $activePeriod) {
            return redirect()->route('applicant.dashboard')
                ->with('error', 'Tidak ada periode pendaftaran yang aktif saat ini.');
        }

        // Cek sudah ada pendaftaran di periode ini
        $existing = Application::where('user_id', Auth::id())
            ->where('admission_period_id', $activePeriod->id)
            ->first();

        if ($existing) {
            return redirect()->route('applicant.application.edit', $existing)
                ->with('info', 'Kamu sudah punya pendaftaran di periode ini.');
        }

        $studyPrograms = StudyProgram::where('is_active', true)->get();

        return view('applicant.application.create', compact('activePeriod', 'studyPrograms'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateForm($request);

        $period = AdmissionPeriod::findOrFail($validated['admission_period_id']);

        if (! $period->isOpen()) {
            return back()->with('error', 'Periode pendaftaran sudah ditutup.');
        }

        // Cek double application
        $existing = Application::where('user_id', Auth::id())
            ->where('admission_period_id', $period->id)
            ->first();

        if ($existing) {
            return redirect()->route('applicant.application.edit', $existing);
        }

        $application = Application::create(array_merge($validated, [
            'user_id'        => Auth::id(),
            'payment_status' => $period->registration_fee > 0 ? 'unpaid' : 'not_required',
        ]));

        return redirect()->route('applicant.document.index', $application)
            ->with('success', 'Data berhasil disimpan. Silakan unggah dokumen.');
    }

    public function edit(Application $application)
    {
        $this->authorize('update', $application);

        if (! $application->isEditable()) {
            return redirect()->route('applicant.application.status', $application)
                ->with('error', 'Pendaftaran tidak bisa diedit pada status ini.');
        }

        $studyPrograms = StudyProgram::where('is_active', true)->get();

        return view('applicant.application.edit', compact('application', 'studyPrograms'));
    }

    public function update(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        if (! $application->isEditable()) {
            abort(403);
        }

        $validated = $this->validateForm($request, $application->id);

        $application->update($validated);

        return redirect()->route('applicant.document.index', $application)
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function summary(Application $application)
    {
        $this->authorize('view', $application);
        $application->load(['studyProgram', 'admissionPeriod', 'documents']);

        return view('applicant.application.summary', compact('application'));
    }

    public function submit(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        if (! $application->isEditable()) {
            return back()->with('error', 'Status tidak memungkinkan submit.');
        }

        if (! $application->admissionPeriod->isOpen()) {
            return back()->with('error', 'Periode pendaftaran sudah ditutup.');
        }

        DB::transaction(function () use ($application) {
            $application->update([
                'status'       => 'submitted',
                'submitted_at' => now(),
            ]);

            if (! $application->registration_number) {
                $application->update([
                    'registration_number' => $this->service->generateRegistrationNumber($application),
                ]);
            }

            $this->service->changeStatus($application, 'submitted', Auth::user(), 'Pendaftaran dikirim oleh pendaftar.');
        });

        return redirect()->route('applicant.application.status', $application)
            ->with('success', 'Pendaftaran berhasil dikirim! Nomor: ' . $application->registration_number);
    }

    public function status(Application $application)
    {
        $this->authorize('view', $application);
        $application->load(['studyProgram', 'admissionPeriod', 'statusHistories.changedBy']);

        return view('applicant.application.status', compact('application'));
    }

    private function validateForm(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'admission_period_id' => 'required|exists:admission_periods,id',
            'study_program_id'    => 'required|exists:study_programs,id',
            'full_name'           => 'required|string|max:255',
            'nik'                 => 'nullable|digits:16',
            'gender'              => 'required|in:male,female',
            'birth_date'          => 'required|date|before:today',
            'birth_place'         => 'required|string|max:100',
            'religion'            => 'nullable|string|max:50',
            'phone'               => 'required|string|max:20',
            'address'             => 'required|string',
            'city'                => 'required|string|max:100',
            'province'            => 'required|string|max:100',
            'postal_code'         => 'nullable|string|max:10',
            'school_name'         => 'required|string|max:255',
            'school_city'         => 'required|string|max:100',
            'major'               => 'nullable|string|max:100',
            'graduation_year'     => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'gpa'                 => 'nullable|numeric|min:0|max:100',
        ]);
    }
}