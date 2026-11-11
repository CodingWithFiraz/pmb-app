<?php

namespace Database\Seeders;

use App\Models\JalurMasuk;
use Illuminate\Database\Seeder;

class JalurMasukSeeder extends Seeder
{
    public function run(): void
    {
        $jalur = [
            [
                'nama'                     => 'Seleksi Nasional Berbasis Prestasi (SNBP)',
                'kode'                     => 'SNBP',
                'deskripsi'                => 'Jalur pendaftaran berdasarkan prestasi akademik/non-akademik, dibebaskan biaya pendaftaran.',
                'urutan'                   => 1,
                'punya_biaya_pendaftaran'  => false,
            ],
            [
                'nama'                     => 'Seleksi Nasional Berbasis Tes (SNBT)',
                'kode'                     => 'SNBT',
                'deskripsi'                => 'Jalur pendaftaran melalui tes berbasis komputer, dibebaskan biaya pendaftaran.',
                'urutan'                   => 2,
                'punya_biaya_pendaftaran'  => false,
            ],
            [
                'nama'                     => 'Jalur Mandiri',
                'kode'                     => 'MANDIRI',
                'deskripsi'                => 'Jalur pendaftaran mandiri dengan tes seleksi, dikenakan biaya pendaftaran.',
                'urutan'                   => 3,
                'punya_biaya_pendaftaran'  => true,
            ],
        ];

        foreach ($jalur as $item) {
            JalurMasuk::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
