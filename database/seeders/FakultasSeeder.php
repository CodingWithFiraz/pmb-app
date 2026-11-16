<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        $fakultas = [
            [
                'nama'      => 'Fakultas Teknik Grafika dan Penerbitan',
                'kode'      => 'TGP',
                'deskripsi' => 'Fakultas yang menaungi bidang teknik grafika, teknik penerbitan, dan bidang teknis terkait.',
            ],
            [
                'nama'      => 'Fakultas Desain',
                'kode'      => 'DSA',
                'deskripsi' => 'Fakultas yang menaungi bidang desain grafis, animasi, dan multimedia.',
            ],
        ];

        foreach ($fakultas as $item) {
            Fakultas::updateOrCreate(['kode' => $item['kode']], array_merge($item, [
                'slug' => Str::slug(Str::after($item['nama'], 'Fakultas ')),
            ]));
        }
    }
}
