<?php

namespace Database\Seeders;

use App\Models\DokumenMaster;
use Illuminate\Database\Seeder;

class DokumenMasterSeeder extends Seeder
{
    public function run(): void
    {
        $dokumen = [
            [
                'nama'      => 'Kartu Tanda Penduduk (KTP)',
                'kode'      => 'KTP',
                'tipe_file' => 'pdf',
                'max_size'  => 2048,
                'is_wajib'  => true,
            ],
            [
                'nama'      => 'Kartu Keluarga (KK)',
                'kode'      => 'KK',
                'tipe_file' => 'pdf',
                'max_size'  => 2048,
                'is_wajib'  => true,
            ],
            [
                'nama'      => 'Pas Foto 3x4',
                'kode'      => 'FOTO',
                'tipe_file' => 'jpg',
                'max_size'  => 512,
                'is_wajib'  => true,
            ],
            [
                'nama'      => 'Ijazah / Surat Keterangan Lulus',
                'kode'      => 'IJAZAH',
                'tipe_file' => 'pdf',
                'max_size'  => 2048,
                'is_wajib'  => true,
            ],
            [
                'nama'      => 'Rapor SMA/SMK',
                'kode'      => 'RAPOR',
                'tipe_file' => 'pdf',
                'max_size'  => 4096,
                'is_wajib'  => true,
            ],
            [
                'nama'      => 'Piagam Prestasi',
                'kode'      => 'PIAGAM',
                'tipe_file' => 'pdf',
                'max_size'  => 2048,
                'is_wajib'  => false,
            ],
        ];

        foreach ($dokumen as $item) {
            DokumenMaster::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
