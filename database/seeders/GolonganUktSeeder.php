<?php

namespace Database\Seeders;

use App\Models\GolonganUkt;
use Illuminate\Database\Seeder;

class GolonganUktSeeder extends Seeder
{
    public function run(): void
    {
        $golongan = [
            [
                'kode'       => 'I',
                'nama'       => 'Golongan I',
                'deskripsi'  => 'Penghasilan orang tua paling tinggi, tarif UKT paling rendah.',
            ],
            [
                'kode'       => 'II',
                'nama'       => 'Golongan II',
                'deskripsi'  => 'Penghasilan orang tua tinggi, tarif UKT rendah.',
            ],
            [
                'kode'       => 'III',
                'nama'       => 'Golongan III',
                'deskripsi'  => 'Penghasilan orang tua menengah, tarif UKT menengah.',
            ],
            [
                'kode'       => 'IV',
                'nama'       => 'Golongan IV',
                'deskripsi'  => 'Penghasilan orang tua menengah ke bawah, tarif UKT tinggi.',
            ],
            [
                'kode'       => 'V',
                'nama'       => 'Golongan V',
                'deskripsi'  => 'Penghasilan orang tua terendah, tarif UKT paling tinggi.',
            ],
        ];

        foreach ($golongan as $item) {
            GolonganUkt::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
