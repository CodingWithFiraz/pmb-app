<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\PeriodePmb;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KartuController extends Controller
{
    public function index(): View|RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $periode = PeriodePmb::getActive();

        $pendaftaran = $user->pendaftaran()
            ->with(['periode', 'jalur', 'prodi', 'golongan', 'pembayaran'])
            ->when($periode, fn ($q) => $q->where('periode_id', $periode->id))
            ->latest()
            ->first();

        // Kartu hanya ditampilkan jika lulus dan semua tagihan lunas.
        $bolehTampilkan = $pendaftaran
            && $pendaftaran->hasil_kelulusan === 'lulus'
            && $this->semuaTagihanLunas($pendaftaran);

        if (! $bolehTampilkan) {
            return redirect()
                ->route('pendaftar.pengumuman')
                ->with('info', 'Kartu peserta hanya tersedia setelah hasil kelulusan dinyatakan lulus dan semua tagihan lunas.');
        }

        Gate::authorize('view', $pendaftaran);

        return view('pendaftar.kartu', compact('pendaftaran'));
    }

    /**
     * Semua tagihan pendaftaran harus lunas.
     */
    private function semuaTagihanLunas(? \App\Models\Pendaftaran $pendaftaran): bool
    {
        if (! $pendaftaran) {
            return false;
        }

        $tagihan = $pendaftaran->pembayaran;

        if ($tagihan->isEmpty()) {
            return false;
        }

        return $tagihan->every(fn ($t) => $t->status === 'paid');
    }
}
