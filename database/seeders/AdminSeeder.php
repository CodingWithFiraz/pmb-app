<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pmb.ac.id'],
            [
                'name' => 'Admin PMB',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}