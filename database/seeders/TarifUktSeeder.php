<?php

namespace Database\Seeders;

use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\TarifUkt;
use Illuminate\Database\Seeder;

class TarifUktSeeder extends Seeder
{
    /**
     * Multiplier tarif per golongan UKT (terhadap tarif dasar golongan I).
     */
    private const MULTIPLIER_GOLONGAN = [
        'I'   => 1.00,
        'II'  => 1.25,
        'III' => 1.50,
        'IV'  => 1.75,
        'V'   => 2.00,
    ];

    /**
     * Multiplier tarif per jalur masuk (terhadap tarif jalur SNBP).
     */
    private const MULTIPLIER_JALUR = [
        'SNBP'    => 1.00,
        'SNBT'    => 1.20,
        'MANDIRI' => 1.40,
    ];

    /**
     * Tarif dasar per prodi (Golongan I, jalur SNBP).
     */
    private const TARIF_DASAR = [
        'TGF' => 4_000_000,
        'TPN' => 3_500_000,
        'DSG' => 4_500_000,
        'ANI' => 5_500_000,
        'MUL' => 5_000_000,
    ];

    public function run(): void
    {
        $periode = PeriodePmb::active()->firstOrFail();

        $jalur = JalurMasuk::orderBy('urutan')->get()->keyBy('kode');
        $prodi = Prodi::orderBy('id')->get()->keyBy('kode');
        $golongan = GolonganUkt::orderBy('kode')->get()->keyBy('kode');

        foreach (self::TARIF_DASAR as $kodeProdi => $dasar) {
            if (! $prodi->has($kodeProdi)) {
                continue;
            }

            foreach (self::MULTIPLIER_GOLONGAN as $kodeGolongan => $multGolongan) {
                foreach (self::MULTIPLIER_JALUR as $kodeJalur => $multJalur) {
                    if (! $jalur->has($kodeJalur) || ! $golongan->has($kodeGolongan)) {
                        continue;
                    }

                    $nominal = (int) round($dasar * $multGolongan * $multJalur);

                    TarifUkt::updateOrCreate(
                        [
                            'periode_id'  => $periode->id,
                            'jalur_id'    => $jalur[$kodeJalur]->id,
                            'prodi_id'    => $prodi[$kodeProdi]->id,
                            'golongan_id' => $golongan[$kodeGolongan]->id,
                        ],
                        ['nominal' => $nominal]
                    );
                }
            }
        }
    }
}
