<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $berita = [
            [
                'judul'        => 'Pendaftaran PMB PNM Jakarta Gelombang 1 Telah Dibuka',
                'slug'         => 'pendaftaran-pmb-pnm-jakarta-gelombang-1-dibuka',
                'deskripsi'    => 'Institut Teknologi dan Bisnis Jakarta membuka pendaftaran mahasiswa baru untuk tahun akademik 2025/2026. Pendaftar dapat memilih jalur SNBP, SNBT, atau Mandiri sesuai prestasi dan kemampuan masing-masing.',
                'author'       => 'Panitia PMB',
                'status'       => 'publish',
                'published_at' => now()->subDays(5),
            ],
            [
                'judul'        => 'Jadwal Ujian Seleksi Jalur Mandiri Diumumkan',
                'slug'         => 'jadwal-ujian-seleksi-jalur-mandiri-diumumkan',
                'deskripsi'    => 'Ujian seleksi jalur Mandiri akan dilaksanakan pada dua gelombang dengan sistem tes berbasis kompetensi. Peserta diperintahkan hadir 30 menit lebih awal dan membawa dokumen identitas asli.',
                'author'       => 'Panitia PMB',
                'status'       => 'publish',
                'published_at' => now()->subDays(3),
            ],
            [
                'judul'        => 'Informasi Tarif UKT Tahun Akademik 2025/2026',
                'slug'         => 'informasi-tarif-ukt-tahun-akademik-2025-2026',
                'deskripsi'    => 'Tarif Uang Kuliah Tunggal dihitung berdasarkan program studi, jalur masuk, dan golongan UKT pendaftar. Jalur Mandiri dikenakan biaya pendaftaran sebesar Rp250.000, sedangkan jalur SNBP dan SNBT dibebaskan biaya pendaftaran.',
                'author'       => 'Bagian Keuangan',
                'status'       => 'publish',
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($berita as $item) {
            Berita::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
