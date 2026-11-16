<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pmb.ac.id'],
            [
                'name'     => 'Admin PMB',
                'role'     => 'admin',
                'password' => Hash::make('admin123'),
                'phone'    => '081200000001',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pendaftar@pmb.ac.id'],
            [
                'name'     => 'Pendaftar Demo',
                'role'     => 'pendaftar',
                'password' => Hash::make('pendaftar123'),
                'phone'    => '08123456789',
            ]
        );
    }
}
