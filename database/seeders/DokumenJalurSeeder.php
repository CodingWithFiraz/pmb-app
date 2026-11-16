<?php

namespace Database\Seeders;

use App\Models\DokumenJalur;
use App\Models\DokumenMaster;
use App\Models\JalurMasuk;
use Illuminate\Database\Seeder;

class DokumenJalurSeeder extends Seeder
{
    /**
     * Kode dokumen yang wajib diunggah per jalur masuk.
     */
    private const DOKUMEN_PER_JALUR = [
        'SNBP'    => ['RAPOR', 'PIAGAM'],
        'SNBT'    => ['RAPOR'],
        'MANDIRI' => ['KTP', 'KK', 'FOTO', 'IJAZAH', 'RAPOR', 'PIAGAM'],
    ];

    public function run(): void
    {
        $jalur = JalurMasuk::orderBy('urutan')->get()->keyBy('kode');

        foreach (self::DOKUMEN_PER_JALUR as $kodeJalur => $kodeDokumen) {
            if (! $jalur->has($kodeJalur)) {
                continue;
            }

            foreach ($kodeDokumen as $kode) {
                $dokumen = DokumenMaster::where('kode', $kode)->firstOrFail();

                DokumenJalur::updateOrCreate(
                    [
                        'jalur_id'   => $jalur[$kodeJalur]->id,
                        'dokumen_id' => $dokumen->id,
                    ]
                );
            }
        }
    }
}
