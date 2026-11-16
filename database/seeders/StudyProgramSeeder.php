<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ['code' => 'TI', 'name' => 'Teknik Informatika', 'faculty' => 'Teknik', 'quota' => 100],
            ['code' => 'SI', 'name' => 'Sistem Informasi', 'faculty' => 'Teknik', 'quota' => 80],
            ['code' => 'MNJ', 'name' => 'Manajemen', 'faculty' => 'Ekonomi & Bisnis', 'quota' => 120],
            ['code' => 'AKT', 'name' => 'Akuntansi', 'faculty' => 'Ekonomi & Bisnis', 'quota' => 100],
            ['code' => 'HKM', 'name' => 'Ilmu Hukum', 'faculty' => 'Hukum', 'quota' => 80],
        ];

        foreach ($programs as $program) {
            StudyProgram::create(array_merge($program, ['is_active' => true]));
        }
    }
}