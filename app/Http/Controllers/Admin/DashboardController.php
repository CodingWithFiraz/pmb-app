<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPeriod;
use App\Models\Application;
use App\Models\JalurMasuk;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PeriodePmb;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hasPendaftaran = class_exists(Pendaftaran::class) && Pendaftaran::count() > 0;

        if ($hasPendaftaran) {
            $total = Pendaftaran::count();
            $sudahBayar = class_exists(Pembayaran::class)
                ? Pendaftaran::whereHas('pembayaran', fn ($query) => $query->where('status', 'paid'))->count()
                : 0;

            $stats = [
                'total'               => $total,
                'draft'               => Pendaftaran::where('status', 'draft')->count(),
                'submitted'           => Pendaftaran::where('status', 'menunggu_verifikasi')->count(),
                'under_review'        => Pendaftaran::where('status', 'menunggu_verifikasi')->count(),
                'menunggu_verifikasi' => Pendaftaran::where('status', 'menunggu_verifikasi')->count(),
                'terverifikasi'       => Pendaftaran::where('status', 'terverifikasi')->count(),
                'revision_required'   => 0,
                'passed'              => Pendaftaran::where('status', 'terverifikasi')->count(),
                'not_passed'          => Pendaftaran::where('status', 'ditolak')->count(),
                'ditolak'             => Pendaftaran::where('status', 'ditolak')->count(),
                'sudah_bayar'         => $sudahBayar,
                'belum_bayar'         => $total - $sudahBayar,
                'menunggu_bayar'      => class_exists(Pembayaran::class) ? Pembayaran::where('status', 'pending')->count() : 0,
            ];
        } else {
            $stats = [
                'total'               => Application::count(),
                'draft'               => Application::where('status', 'draft')->count(),
                'submitted'           => Application::where('status', 'submitted')->count(),
                'under_review'        => Application::where('status', 'under_review')->count(),
                'menunggu_verifikasi' => Application::where('status', 'submitted')->count(),
                'terverifikasi'       => Application::where('status', 'passed')->count(),
                'revision_required'   => Application::where('status', 'revision_required')->count(),
                'passed'              => Application::where('status', 'passed')->count(),
                'not_passed'          => Application::where('status', 'not_passed')->count(),
                'ditolak'             => Application::where('status', 'not_passed')->count(),
            ];
        }

        $activePeriod = AdmissionPeriod::where('status', 'open')->first()
            ?? (class_exists(PeriodePmb::class) ? PeriodePmb::getActive() : null);

        $recentApplicants = Application::with(['user', 'studyProgram'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'activePeriod', 'recentApplicants'));
    }
}
