<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Master data
            UserSeeder::class,
            GolonganUktSeeder::class,
            JalurMasukSeeder::class,
            FakultasSeeder::class,
            ProdiSeeder::class,
            PeriodePmbSeeder::class,
            DokumenMasterSeeder::class,

            // Relasi master data
            PendaftarProfileSeeder::class,
            DokumenJalurSeeder::class,
            TarifUktSeeder::class,
            BiayaPendaftaranSeeder::class,

            // Konten & pengaturan
            BeritaSeeder::class,
            FaqSeeder::class,
            PengaturanSeeder::class,
        ]);
    }
}
