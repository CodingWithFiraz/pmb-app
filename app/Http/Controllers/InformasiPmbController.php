<?php

namespace App\Http\Controllers;

use App\Models\BiayaPendaftaran;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\TarifUkt;

class InformasiPmbController extends Controller
{
    public function index()
    {
        $periode = PeriodePmb::getActive();

        $jalur = JalurMasuk::orderBy('urutan')->get();

        $biaya = collect();
        $tarif = collect();

        if ($periode) {
            $biaya = BiayaPendaftaran::with('jalur')
                ->where('periode_id', $periode->id)
                ->get()
                ->keyBy('jalur.nama');

            $tarif = TarifUkt::with(['prodi', 'golongan'])
                ->where('periode_id', $periode->id)
                ->orderBy('prodi_id')
                ->orderBy('golongan_id')
                ->get()
                ->groupBy('jalur_id');

            // Bentuk struktur per jalur: prodi (terisi) + peta tarif per prodi.
            $tarif = $tarif->map(function ($rows, $jalurId) {
                $prodiIds = $rows->pluck('prodi_id')->unique();

                $prodi = Prodi::whereIn('id', $prodiIds)->orderBy('nama')->get();

                $peta = [];

                foreach ($prodi as $p) {
                    $peta[$p->id] = $rows
                        ->where('prodi_id', $p->id)
                        ->keyBy('golongan_id');
                }

                return ['prodi' => $prodi, 'tarif' => $peta];
            });

            // Sinkronkan urutan dengan daftar jalur.
            $tarif = $jalur->mapWithKeys(fn ($j) => [$j->nama => $tarif[$j->id] ?? ['prodi' => collect(), 'tarif' => []]]);
        }

        $prodiPerFakultas = Prodi::with('fakultas')->orderBy('nama')->get()->groupBy('fakultas.nama');

        $golongan = GolonganUkt::orderBy('kode')->get();

        return view('public.informasi-pmb', compact(
            'periode', 'jalur', 'biaya', 'tarif', 'prodiPerFakultas', 'golongan'
        ));
    }
}
