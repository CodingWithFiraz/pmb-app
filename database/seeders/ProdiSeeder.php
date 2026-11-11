<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = [
            ['kode' => 'TGF', 'fakultas' => 'TGP', 'nama' => 'Teknik Grafika',    'jenjang' => 'S1', 'kuota' => 120, 'deskripsi' => 'Program Studi Teknik Grafika berbasis produksi grafika dan percetakan.'],
            ['kode' => 'TPN', 'fakultas' => 'TGP', 'nama' => 'Teknik Penerbitan', 'jenjang' => 'S1', 'kuota' => 100, 'deskripsi' => 'Program Studi Teknik Penerbitan berbasis industri media.'],
            ['kode' => 'DSG', 'fakultas' => 'DSA', 'nama' => 'Desain Grafis',    'jenjang' => 'S1', 'kuota' => 140, 'deskripsi' => 'Program Studi Desain Grafis berbasis komunikasi visual dan branding.'],
            ['kode' => 'ANI', 'fakultas' => 'DSA', 'nama' => 'Animasi',          'jenjang' => 'S1', 'kuota' => 90,  'deskripsi' => 'Program Studi Animasi berbasis praktik studio animasi 2D dan 3D.'],
            ['kode' => 'MUL', 'fakultas' => 'DSA', 'nama' => 'Multimedia',       'jenjang' => 'S1', 'kuota' => 110, 'deskripsi' => 'Program Studi Multimedia berbasis produksi konten digital interaktif.'],
        ];

        foreach ($prodi as $item) {
            $fakultas = Fakultas::where('kode', $item['fakultas'])->firstOrFail();

            Prodi::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'fakultas_id' => $fakultas->id,
                    'nama'        => $item['nama'],
                    'slug'        => Str::slug($item['nama']),
                    'jenjang'     => $item['jenjang'],
                    'deskripsi'   => $item['deskripsi'],
                    'kuota'       => $item['kuota'],
                ]
            );
        }
    }
}
