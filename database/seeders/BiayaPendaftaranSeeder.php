<?php

namespace Database\Seeders;

use App\Models\BiayaPendaftaran;
use App\Models\JalurMasuk;
use App\Models\PeriodePmb;
use Illuminate\Database\Seeder;

class BiayaPendaftaranSeeder extends Seeder
{
    /**
     * Nominal biaya pendaftaran per jalur (dalam rupiah).
     * Jalur SNBP dan SNBT dibebaskan biaya, sehingga null.
     */
    private const NOMINAL = [
        'SNBP'    => null,
        'SNBT'    => null,
        'MANDIRI' => 250_000,
    ];

    public function run(): void
    {
        $periode = PeriodePmb::active()->firstOrFail();

        foreach (JalurMasuk::orderBy('urutan')->get() as $jalur) {
            if (! array_key_exists($jalur->kode, self::NOMINAL)) {
                continue;
            }

            BiayaPendaftaran::updateOrCreate(
                [
                    'periode_id' => $periode->id,
                    'jalur_id'   => $jalur->id,
                ],
                ['nominal' => self::NOMINAL[$jalur->kode]]
            );
        }
    }
}
