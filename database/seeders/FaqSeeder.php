<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faq = [
            [
                'pertanyaan' => 'Apa saja jalur pendaftaran yang tersedia?',
                'jawaban'    => 'Tersedia tiga jalur pendaftaran, yaitu SNBP (Seleksi Nasional Berbasis Prestasi), SNBT (Seleksi Nasional Berbasis Tes), dan Mandiri. Jalur SNBP dan SNBT dibebaskan biaya pendaftaran, sedangkan jalur Mandiri dikenakan biaya pendaftaran Rp250.000.',
                'kategori'   => 'Pendaftaran',
                'urutan'     => 1,
            ],
            [
                'pertanyaan' => 'Bagaimana cara menentukan golongan UKT saya?',
                'jawaban'    => 'Golongan UKT ditetapkan otomatis dari penghasilan orang tua/wali per bulan: Golongan I untuk penghasilan sampai Rp2.999.999, Golongan II untuk Rp3.000.000–Rp4.999.999, Golongan III untuk Rp5.000.000–Rp8.999.999, dan Golongan V untuk penghasilan Rp9.000.000 atau lebih. Pendaftar tidak dapat memilih golongan secara manual.',
                'kategori'   => 'Biaya',
                'urutan'     => 2,
            ],
            [
                'pertanyaan' => 'Dokumen apa saja yang harus diunggah?',
                'jawaban'    => 'Kebutuhan dokumen berbeda-beda sesuai jalur masuk. Jalur SNBP wajib melampirkan rapor dan piagam prestasi, jalur SNBT wajib melampirkan rapor, sedangkan jalur Mandiri wajib melampirkan KTP, KK, pas foto, ijazah/surat keterangan lulus, rapor, dan piagam prestasi.',
                'kategori'   => 'Dokumen',
                'urutan'     => 3,
            ],
        ];

        foreach ($faq as $item) {
            Faq::updateOrCreate(
                ['pertanyaan' => $item['pertanyaan']],
                $item
            );
        }
    }
}
