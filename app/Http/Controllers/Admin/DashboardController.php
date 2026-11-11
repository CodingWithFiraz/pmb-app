<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\PeriodePmb;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $total = Pendaftaran::count();

        $sudahBayar = Pendaftaran::whereHas('pembayaran', fn ($query) => $query->where('status', 'paid'))->count();

        $stats = [
            'total'               => $total,
            'draft'               => Pendaftaran::where('status', 'draft')->count(),
            'menunggu_verifikasi' => Pendaftaran::where('status', 'menunggu_verifikasi')->count(),
            'terverifikasi'       => Pendaftaran::where('status', 'terverifikasi')->count(),
            'ditolak'             => Pendaftaran::where('status', 'ditolak')->count(),
            'sudah_bayar'         => $sudahBayar,
            'belum_bayar'         => $total - $sudahBayar,
            'menunggu_bayar'      => Pembayaran::where('status', 'pending')->count(),
        ];

        $perJalur = Pendaftaran::query()
            ->selectRaw('jalur_id, COUNT(*) as total')
            ->groupBy('jalur_id')
            ->pluck('total', 'jalur_id');

        $jalurList = JalurMasuk::orderBy('urutan')->get()
            ->map(fn (JalurMasuk $jalur): array => [
                'kode'  => $jalur->kode,
                'nama'  => $jalur->nama,
                'total' => (int) ($perJalur[$jalur->id] ?? 0),
            ]);

        $activePeriod = PeriodePmb::getActive();

        $ringkasanTagihan = [
            'total'   => Pembayaran::count(),
            'paid'    => Pembayaran::where('status', 'paid')->count(),
            'pending' => Pembayaran::where('status', 'pending')->count(),
            'gagal'   => Pembayaran::whereIn('status', ['expired', 'failed'])->count(),
        ];

        $recentPendaftar = Pendaftaran::with(['user', 'prodi', 'jalur'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'activePeriod',
            'recentPendaftar',
            'jalurList',
            'ringkasanTagihan'
        ));
    }
}
