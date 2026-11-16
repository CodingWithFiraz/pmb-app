<?php

namespace Database\Seeders;

use App\Models\AdmissionPeriod;
use Illuminate\Database\Seeder;

class AdmissionPeriodSeeder extends Seeder
{
    public function run(): void
    {
        AdmissionPeriod::create([
            'name'             => 'PMB Reguler 2025/2026',
            'academic_year'    => '2025/2026',
            'starts_at'        => now(),
            'ends_at'          => now()->addMonths(3),
            'registration_fee' => 0,
            'status'           => 'open',
        ]);
    }
}