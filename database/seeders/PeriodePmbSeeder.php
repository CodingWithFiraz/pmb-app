<?php

namespace Database\Seeders;

use App\Models\PeriodePmb;
use Illuminate\Database\Seeder;

class PeriodePmbSeeder extends Seeder
{
    public function run(): void
    {
        PeriodePmb::updateOrCreate(
            ['tahun_akademik' => '2025/2026'],
            [
                'nama'            => 'PMB Gelombang 1 Tahun Akademik 2025/2026',
                'tanggal_mulai'   => now()->startOfMonth()->toDateString(),
                'tanggal_selesai' => now()->addMonths(5)->endOfMonth()->toDateString(),
                'is_active'       => true,
            ]
        );
    }
}
