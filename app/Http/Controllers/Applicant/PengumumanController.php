<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PeriodePmb;
use App\Models\User;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $periode = PeriodePmb::getActive();

        $pendaftaran = $user->pendaftaran()
            ->with(['periode', 'jalur', 'prodi', 'golongan'])
            ->when($periode, fn ($q) => $q->where('periode_id', $periode->id))
            ->latest()
            ->first();

        $tagihanLunas = true;

        if ($pendaftaran) {
            $tagihan = $pendaftaran->pembayaran;

            foreach ($tagihan as $t) {
                if ($t->status !== 'paid') {
                    $tagihanLunas = false;
                    break;
                }
            }
        }

        $tampilKartu = $pendaftaran
            && $pendaftaran->hasil_kelulusan === 'lulus'
            && $tagihanLunas;

        return view('pendaftar.pengumuman', compact(
            'pendaftaran',
            'tagihanLunas',
            'tampilKartu'
        ));
    }
}
