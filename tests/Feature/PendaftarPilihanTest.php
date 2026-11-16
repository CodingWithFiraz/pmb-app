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

class PendaftarPilihanTest extends TestCase
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

    public function test_halaman_pilihan_menampilkan_form_dan_preview_ukt(): void
    {
        $this->actingAs($this->pendaftar())
            ->get('/pendaftar/pilihan')
            ->assertOk()
            ->assertSee('Pilihan Pendaftaran')
            ->assertSee('Perkiraan UKT per tahun')
            ->assertSee('Golongan UKT');
    }

    public function test_submit_pertama_membuat_pendaftaran_status_draft(): void
    {
        $user = $this->pendaftar();
        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::where('kode', 'SNBT')->firstOrFail();
        $prodi = Prodi::where('kode', 'TGF')->firstOrFail();
        $golonganProfil = $user->profile->golongan_id;
        $golonganLain = GolonganUkt::where('kode', 'III')->firstOrFail();

        $this->actingAs($user)
            ->post('/pendaftar/pilihan', [
                'jalur_id'    => $jalur->id,
                'prodi_1_id'  => $prodi->id,
                // Pilihan kedua boleh sama dengan pilihan pertama.
                'prodi_2_id'  => $prodi->id,
                'golongan_id' => $golonganLain->id,
            ])
            ->assertRedirect(route('pendaftar.pilihan'));

        $this->assertDatabaseHas('pendaftaran', [
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'prodi_2_id'  => $prodi->id,
            'golongan_id' => $golonganProfil,
            'status'      => 'draft',
        ]);

        $this->assertSame($golonganProfil, $user->fresh()->profile->golongan_id);
    }

    public function test_prodi_1_wajib_diisi(): void
    {
        $user = $this->pendaftar();
        $jalur = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $golongan = GolonganUkt::where('kode', 'I')->firstOrFail();

        $this->actingAs($user)
            ->from('/pendaftar/pilihan')
            ->post('/pendaftar/pilihan', [
                'jalur_id'    => $jalur->id,
                'prodi_2_id'  => Prodi::firstOrFail()->id,
                'golongan_id' => $golongan->id,
            ])
            ->assertRedirect('/pendaftar/pilihan')
            ->assertSessionHasErrors('prodi_1_id');

        $this->assertDatabaseCount('pendaftaran', 0);
    }

    public function test_preview_ukt_berubah_saat_ganti_jalur_prodi_dan_golongan(): void
    {
        $user = $this->pendaftar();
        $this->actingAs($user);

        $periode = PeriodePmb::active()->firstOrFail();

        $snbp = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $mandiri = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();
        $tgf = Prodi::where('kode', 'TGF')->firstOrFail();
        $dsg = Prodi::where('kode', 'DSG')->firstOrFail();
        $gol1 = GolonganUkt::where('kode', 'I')->firstOrFail();
        $gol5 = GolonganUkt::where('kode', 'V')->firstOrFail();

        $ambil = function (JalurMasuk $jalur, Prodi $prodi, GolonganUkt $golongan) use ($periode): int {
            $expected = TarifUkt::where('periode_id', $periode->id)
                ->where('jalur_id', $jalur->id)
                ->where('prodi_id', $prodi->id)
                ->where('golongan_id', $golongan->id)
                ->value('nominal');

            $response = $this->getJson(route('pendaftar.pilihan.preview', [
                'jalur_id'    => $jalur->id,
                'prodi_1_id'  => $prodi->id,
                'golongan_id' => $golongan->id,
            ]));

            $response->assertOk()
                ->assertJson([
                    'nominal'   => $expected,
                    'formatted' => 'Rp '.number_format($expected, 0, ',', '.'),
                ]);

            return (int) $response->json('nominal');
        };

        $awal = $ambil($snbp, $tgf, $gol1);
        $bedaJalur = $ambil($mandiri, $tgf, $gol1);
        $bedaProdi = $ambil($snbp, $dsg, $gol1);
        $bedaGolongan = $ambil($snbp, $tgf, $gol5);

        $this->assertNotSame($awal, $bedaJalur, 'UKT harus berubah saat jalur diganti.');
        $this->assertNotSame($awal, $bedaProdi, 'UKT harus berubah saat prodi diganti.');
        $this->assertNotSame($awal, $bedaGolongan, 'UKT harus berubah saat golongan diganti.');
    }

    public function test_preview_menampilkan_biaya_pendaftaran_sesuai_jalur(): void
    {
        $user = $this->pendaftar();
        $golongan = GolonganUkt::where('kode', 'I')->firstOrFail();
        $prodi = Prodi::firstOrFail();

        $mandiri = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();
        $snbp = JalurMasuk::where('kode', 'SNBP')->firstOrFail();

        $this->actingAs($user)
            ->getJson(route('pendaftar.pilihan.preview', [
                'jalur_id'    => $mandiri->id,
                'prodi_1_id'  => $prodi->id,
                'golongan_id' => $golongan->id,
            ]))
            ->assertOk()
            ->assertJson(['biaya_label' => 'Rp 250.000']);

        $this->getJson(route('pendaftar.pilihan.preview', [
            'jalur_id'    => $snbp->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
        ]))
            ->assertOk()
            ->assertJson(['biaya_label' => 'Gratis']);
    }

    public function test_pilihan_draft_masih_bisa_diubah(): void
    {
        $user = $this->pendaftar();
        $periode = PeriodePmb::active()->firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::firstOrFail();
        $snbp = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $mandiri = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();

        $pendaftaran = Pendaftaran::create([
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $snbp->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => 'draft',
        ]);

        $this->actingAs($user)
            ->post('/pendaftar/pilihan', [
                'jalur_id'    => $mandiri->id,
                'prodi_1_id'  => $prodi->id,
                'golongan_id' => $golongan->id,
            ])
            ->assertRedirect(route('pendaftar.pilihan'));

        $this->assertDatabaseHas('pendaftaran', [
            'id'       => $pendaftaran->id,
            'jalur_id' => $mandiri->id,
            'status'   => 'draft',
        ]);
    }

    public function test_jalur_tidak_bisa_diubah_setelah_pendaftaran_diverifikasi(): void
    {
        $user = $this->pendaftar();
        $periode = PeriodePmb::active()->firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::firstOrFail();
        $snbp = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $mandiri = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();

        $pendaftaran = Pendaftaran::create([
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $snbp->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => 'terverifikasi',
        ]);

        $this->actingAs($user)
            ->post('/pendaftar/pilihan', [
                'jalur_id'    => $mandiri->id,
                'prodi_1_id'  => $prodi->id,
                'golongan_id' => $golongan->id,
            ])
            ->assertRedirect(route('pendaftar.pilihan'))
            ->assertSessionHas('error');

        // Jalur lama tidak berubah.
        $this->assertDatabaseHas('pendaftaran', [
            'id'       => $pendaftaran->id,
            'jalur_id' => $snbp->id,
            'status'   => 'terverifikasi',
        ]);

        // Halaman pilihan menampilkan status terkunci.
        $this->get('/pendaftar/pilihan')
            ->assertOk()
            ->assertSee('dikunci');
    }

    public function test_prodi_2_opsional(): void
    {
        $user = $this->pendaftar();
        $jalur = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::firstOrFail();

        $this->actingAs($user)
            ->post('/pendaftar/pilihan', [
                'jalur_id'    => $jalur->id,
                'prodi_1_id'  => $prodi->id,
                'golongan_id' => $golongan->id,
            ])
            ->assertRedirect(route('pendaftar.pilihan'));

        $this->assertDatabaseHas('pendaftaran', [
            'user_id'   => $user->id,
            'prodi_1_id' => $prodi->id,
            'prodi_2_id' => null,
        ]);
    }
}
