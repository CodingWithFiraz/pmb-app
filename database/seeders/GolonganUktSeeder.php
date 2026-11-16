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
                'kode'      => 'I',
                'nama'      => 'Golongan I',
                'deskripsi' => 'Penghasilan orang tua ≤ Rp2.999.999 per bulan.',
            ],
            [
                'kode'      => 'II',
                'nama'      => 'Golongan II',
                'deskripsi' => 'Penghasilan orang tua Rp3.000.000–Rp4.999.999 per bulan.',
            ],
            [
                'kode'      => 'III',
                'nama'      => 'Golongan III',
                'deskripsi' => 'Penghasilan orang tua Rp5.000.000–Rp8.999.999 per bulan.',
            ],
            [
                'kode'      => 'IV',
                'nama'      => 'Golongan IV',
                'deskripsi' => 'Tidak ditetapkan otomatis dari penghasilan orang tua.',
            ],
            [
                'kode'      => 'V',
                'nama'      => 'Golongan V',
                'deskripsi' => 'Penghasilan orang tua ≥ Rp9.000.000 per bulan.',
            ],
        ];

        foreach ($golongan as $item) {
            GolonganUkt::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
