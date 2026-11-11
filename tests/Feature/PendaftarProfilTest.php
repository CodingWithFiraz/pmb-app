<?php

namespace Tests\Feature;

use App\Models\GolonganUkt;
use App\Models\PendaftarProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftarProfilTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function pendaftar(): User
    {
        return User::where('email', 'pendaftar@pmb.ac.id')->firstOrFail();
    }

    public function test_guest_diarahkan_ke_login(): void
    {
        $this->get('/pendaftar/profil')->assertRedirect('/login');
    }

    public function test_admin_tidak_bisa_mengakses_profil_pendaftar(): void
    {
        $admin = User::where('email', 'admin@pmb.ac.id')->firstOrFail();

        $this->actingAs($admin)->get('/pendaftar/profil')->assertForbidden();
    }

    public function test_profil_multi_step_menyimpan_draft_bertahap(): void
    {
        $user = User::factory()->create(['role' => 'pendaftar']);
        $this->actingAs($user);

        // Belum punya profil: diarahkan ke langkah 1.
        $this->get('/pendaftar/profil')->assertRedirect(route('pendaftar.profil.step', 1));

        // Langkah 1: data diri.
        $this->post(route('pendaftar.profil.step.update', 1), [
            'nik'           => '3171012305980002',
            'nisn'          => '0123456790',
            'tempat_lahir'  => 'Jakarta',
            'tanggal_lahir' => '2006-01-15',
            'jenis_kelamin' => 'L',
        ])->assertRedirect(route('pendaftar.profil.step', 2));

        $profile = $user->fresh()->profile;

        $this->assertNotNull($profile);
        $this->assertSame('3171012305980002', $profile->nik);

        // Draft: field langkah berikutnya masih kosong.
        $this->assertNull($profile->alamat);
        $this->assertNull($profile->golongan_id);

        // 5 dari 14 field wajib terisi.
        $this->assertSame(36, $profile->completionPercentage());

        // Langkah 2: kontak & alamat.
        $this->post(route('pendaftar.profil.step.update', 2), [
            'alamat'   => 'Jl. Pengujian No. 10',
            'provinsi' => 'DKI Jakarta',
            'kota'     => 'Jakarta Selatan',
        ])->assertRedirect(route('pendaftar.profil.step', 3));

        // Langkah 3: asal sekolah.
        $this->post(route('pendaftar.profil.step.update', 3), [
            'asal_sekolah' => 'SMA Negeri 2 Jakarta',
            'tahun_lulus'  => 2024,
        ])->assertRedirect(route('pendaftar.profil.step', 4));

        // Langkah 4: orang tua & golongan UKT.
        $golongan = GolonganUkt::where('kode', 'II')->firstOrFail();

        $this->post(route('pendaftar.profil.step.update', 4), [
            'nama_ortu'        => 'Budi Penguji',
            'pekerjaan_ortu'   => 'Karyawan',
            'penghasilan_ortu' => 4_500_000,
            'golongan_id'      => $golongan->id,
        ])->assertRedirect(route('pendaftar.pilihan'));

        $profile->refresh();

        $this->assertSame(100, $profile->completionPercentage());
        $this->assertSame($golongan->id, $profile->golongan_id);
    }

    public function test_langkah_1_mewajibkan_field_dan_format_nik_nisn(): void
    {
        $user = User::factory()->create(['role' => 'pendaftar']);

        $this->actingAs($user)
            ->from(route('pendaftar.profil.step', 1))
            ->post(route('pendaftar.profil.step.update', 1), [
                'nik'  => '123',
                'nisn' => '456',
            ])
            ->assertRedirect(route('pendaftar.profil.step', 1))
            ->assertSessionHasErrors(['nik', 'nisn', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin']);

        $this->assertDatabaseCount('pendaftar_profiles', 1); // hanya profil dari seeder
    }

    public function test_nik_dan_nisn_harus_unik(): void
    {
        $user = User::factory()->create(['role' => 'pendaftar']);
        $existing = PendaftarProfile::where('user_id', User::where('email', 'pendaftar@pmb.ac.id')->value('id'))->firstOrFail();

        $this->actingAs($user)
            ->from(route('pendaftar.profil.step', 1))
            ->post(route('pendaftar.profil.step.update', 1), [
                'nik'           => $existing->nik,
                'nisn'          => $existing->nisn,
                'tempat_lahir'  => 'Bandung',
                'tanggal_lahir' => '2006-02-20',
                'jenis_kelamin' => 'P',
            ])
            ->assertSessionHasErrors(['nik', 'nisn']);
    }

    public function test_menyimpan_ulang_langkah_yang_sama_tidak_kena_validasi_unik(): void
    {
        $user = User::factory()->create(['role' => 'pendaftar']);

        $data = [
            'nik'           => '3171012305980003',
            'nisn'          => '0123456791',
            'tempat_lahir'  => 'Bogor',
            'tanggal_lahir' => '2006-03-10',
            'jenis_kelamin' => 'P',
        ];

        $this->actingAs($user)->post(route('pendaftar.profil.step.update', 1), $data);

        $this->post(route('pendaftar.profil.step.update', 1), $data)
            ->assertRedirect(route('pendaftar.profil.step', 2))
            ->assertSessionHasNoErrors();
    }

    public function test_setiap_halaman_langkah_profil_dapat_dibuka(): void
    {
        $user = $this->pendaftar();

        foreach ([1, 2, 3, 4] as $step) {
            $this->actingAs($user)
                ->get(route('pendaftar.profil.step', $step))
                ->assertOk()
                ->assertSee('Langkah '.$step);
        }
    }

    public function test_langkah_tidak_dikenal_menghasilkan_404(): void
    {
        $user = User::factory()->create(['role' => 'pendaftar']);

        $this->actingAs($user)->get('/pendaftar/profil/5')->assertNotFound();
    }
}
