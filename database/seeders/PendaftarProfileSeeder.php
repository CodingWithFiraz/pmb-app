<?php

namespace Database\Seeders;

use App\Models\GolonganUkt;
use App\Models\PendaftarProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class PendaftarProfileSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'pendaftar@pmb.ac.id')->first();

        if (! $user) {
            return;
        }

        $golongan = GolonganUkt::where('kode', 'II')->firstOrFail();

        PendaftarProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik'              => '3171012305980001',
                'nisn'             => '0123456789',
                'tempat_lahir'     => 'Jakarta',
                'tanggal_lahir'    => '1998-05-23',
                'jenis_kelamin'    => 'L',
                'alamat'           => 'Jl. Melati No. 12, RT 004 / RW 002, Cipinang Cemara, Jatinegara',
                'provinsi'         => 'DKI Jakarta',
                'kota'             => 'Jakarta Timur',
                'asal_sekolah'     => 'SMA Negeri 1 Jakarta Timur',
                'tahun_lulus'      => 2016,
                'nama_ortu'        => 'Budi Santoso',
                'pekerjaan_ortu'   => 'Wiraswasta',
                'penghasilan_ortu' => 4_500_000,
                'golongan_id'      => $golongan->id,
            ]
        );
    }
}
