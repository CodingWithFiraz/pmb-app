<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $pengaturan = [
            // Identitas kampus
            'nama_kampus'        => 'Institut Teknologi dan Bisnis Jakarta (PNM Jakarta)',
            'nama_singkat'       => 'PNM Jakarta',
            'tagline'            => 'Membangun talenta kreatif digital yang siap bersaing di industri global.',
            'alamat_kampus'     => 'Jl. Raya Bekasi No. 45, Jakarta Timur, DKI Jakarta 12560',
            'email_kampus'       => 'info@pnmjakarta.ac.id',
            'telepon_kampus'     => '021-5001234',
            'wa_kampus'          => '0812-5000-1234',
            'nama_pendaftaran'   => 'Panitia PMB PNM Jakarta',
            'biaya_pendaftaran' => '250000',

            // Halaman Tentang Kampus
            'visi' => 'Menjadi institusi pendidikan tinggi bertaraf dunia yang menghasilkan lulusan berdaya saing global di bidang teknologi dan bisnis kreatif.',
            'misi' => "Menyelenggarakan pendidikan tinggi yang berorientasi pada semangat berkarya.\nMengembangkan riset terapan yang menjawab persoalan industri dan masyarakat.\nMenyelenggarakan pembelajaran berbasis proyek bersama mitra industri.\nMembina lingkungan kampus yang inklusif, kolaboratif, dan berkarakter.",
            'sejarah' => 'PNM Jakarta lahir dari penggabungan politeknik teknik dan sekolah tinggi desain yang telah lama melayani industri kreatif dan manufaktur di Jakarta. Gabungan ini membentuk sebuah ekosistem pendidikan yang menyatukan bidang teknik, desain, dan bisnis dalam satu rumah studi.',
            'sambutan' => 'Selamat datang di PNM Jakarta. Sebagai Ketua Institusi, kami menyampaikan sambutan hangat bagi seluruh calon mahasiswa. Kami percaya bahwa pendidikan tinggi bukan sekadar gelar, melainkan proses panjang untuk membentuk karakter, etos kerja, dan kemampuan praktis dalam berkarya.',
            'sambutan_nama' => 'Dr. Hendra Wijaya, M.M.',
            'sambutan_jabatan' => 'Ketua Institusi',

            // Halaman Bantuan / Kontak
            'jam_operasional' => 'Senin - Jumat, 08.00 - 16.00 WIB (Sabtu 08.00 - 12.00 WIB)',
            'maps_embed' => 'https://www.google.com/maps?q=Institut+Teknologi+dan+Bisnis+Jakarta&output=embed',
        ];

        foreach ($pengaturan as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
