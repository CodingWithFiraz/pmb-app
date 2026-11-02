<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function __construct(protected ApplicationService $service) {}

    public function index(Request $request)
    {
        $query = Application::with(['user', 'studyProgram', 'admissionPeriod'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$search}%"));
            });
        }

        $applications = $query->paginate(20)->withQueryString();

        return view('admin.applications.index', compact('applications'));
    }

    public function show(Application $application)
    {
        $application->load([
            'user', 'studyProgram', 'admissionPeriod',
            'documents', 'statusHistories.changedBy',
        ]);

        return view('admin.applications.show', compact('application'));
    }

    public function review(Request $request, Application $application)
    {
        $request->validate([
            'action' => 'required|in:under_review,revision_required',
            'note'   => 'required_if:action,revision_required|nullable|string|max:1000',
        ]);

        $validTransitions = [
            'submitted'    => ['under_review', 'revision_required'],
            'under_review' => ['revision_required'],
        ];

        if (! in_array($request->action, $validTransitions[$application->status] ?? [])) {
            return back()->with('error', 'Transisi status tidak valid.');
        }

        $application->update(['reviewed_by' => auth()->id()]);

        $this->service->changeStatus(
            $application,
            $request->action,
            auth()->user(),
            $request->note
        );

        if ($request->action === 'revision_required') {
            $application->update(['admin_note' => $request->note]);
        }

        return back()->with('success', 'Status pendaftaran diperbarui.');
    }

    public function decide(Request $request, Application $application)
    {
        $request->validate([
            'decision' => 'required|in:passed,not_passed',
            'note'     => 'nullable|string|max:1000',
        ]);

        if (! in_array($application->status, ['under_review', 'submitted'])) {
            return back()->with('error', 'Status tidak memungkinkan keputusan akhir.');
        }

        $this->service->changeStatus(
            $application,
            $request->decision,
            auth()->user(),
            $request->note
        );

        $application->update(['admin_note' => $request->note]);

        return back()->with('success', 'Keputusan seleksi berhasil disimpan.');
    }

    public function downloadDocument(Application $application, \App\Models\ApplicationDocument $document)
    {
        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_name
        );
    }

    public function export(Request $request)
    {
        $query = Application::with(['user', 'studyProgram', 'admissionPeriod']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->get();

        $headers  = ['Content-Type' => 'text/csv; charset=UTF-8'];
        $filename = 'pendaftar-' . now()->format('Y-m-d') . '.csv';

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');
            // BOM untuk Excel agar bisa baca UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'No. Pendaftaran', 'Nama', 'Email', 'Program Studi',
                'Periode', 'Status', 'Tgl Submit',
            ]);

            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->registration_number,
                    $app->full_name,
                    $app->user->email,
                    $app->studyProgram->name,
                    $app->admissionPeriod->name,
                    $app->status_label,
                    $app->submitted_at?->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, array_merge($headers, [
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]));
    }
}