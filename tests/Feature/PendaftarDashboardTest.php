<?php

namespace Tests\Feature;

use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\TarifUkt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftarDashboardTest extends TestCase
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

    public function test_dashboard_menampilkan_kartu_progres_dan_checklist(): void
    {
        $this->actingAs($this->pendaftar())
            ->get('/pendaftar/dashboard')
            ->assertOk()
            ->assertSee('Profil')
            ->assertSee('100%')
            ->assertSee('Dokumen')
            ->assertSee('Status Verifikasi')
            ->assertSee('Status Pembayaran')
            ->assertSee('Checklist Pendaftaran')
            ->assertSee('Belum mendaftar');
    }

    public function test_dashboard_menampilkan_ukt_dan_status_pendaftaran(): void
    {
        $user = $this->pendaftar();
        $periode = PeriodePmb::active()->firstOrFail();
        $snbp = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::where('kode', 'II')->firstOrFail();

        Pendaftaran::create([
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $snbp->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => 'draft',
        ]);

        $ukt = TarifUkt::where('periode_id', $periode->id)
            ->where('jalur_id', $snbp->id)
            ->where('prodi_id', $prodi->id)
            ->where('golongan_id', $golongan->id)
            ->value('nominal');

        $this->actingAs($user)
            ->get('/pendaftar/dashboard')
            ->assertOk()
            ->assertSee('Draft')
            ->assertSee('0/2')
            ->assertSee('Gratis')
            ->assertSee('Rp '.number_format($ukt, 0, ',', '.'));
    }

    public function test_persen_pendaftaran_menampilkan_persentase_profil(): void
    {
        $user = User::factory()->create(['role' => 'pendaftar']);

        \App\Models\PendaftarProfile::create([
            'user_id'       => $user->id,
            'nik'           => '3171012305980009',
            'nisn'          => '0123456799',
            'tempat_lahir'  => 'Jakarta',
            'tanggal_lahir' => '2006-04-01',
            'jenis_kelamin' => 'L',
        ]);

        // 5 dari 14 field wajib terisi.
        $this->actingAs($user)
            ->get('/pendaftar/dashboard')
            ->assertOk()
            ->assertSee('36%')
            ->assertSee('Lengkapi profil');
    }
}
