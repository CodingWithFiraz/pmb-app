<?php

namespace Tests\Feature;

use App\Models\BiayaPendaftaran;
use App\Models\DokumenJalur;
use App\Models\Fakultas;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\TarifUkt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeedTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_seeder_membuat_dua_user_dengan_role_benar(): void
    {
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', ['email' => 'admin@pmb.ac.id', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'pendaftar@pmb.ac.id', 'role' => 'pendaftar']);
    }

    public function test_master_data_terisi(): void
    {
        $this->assertDatabaseCount('jalur_masuk', 3);
        $this->assertDatabaseCount('fakultas', 2);
        $this->assertDatabaseCount('prodi', 5);
        $this->assertDatabaseCount('golongan_ukt', 5);
        $this->assertDatabaseCount('periode_pmb', 1);
        $this->assertDatabaseCount('dokumen_master', 6);
        $this->assertDatabaseCount('berita', 3);
        $this->assertDatabaseCount('faq', 3);

        $this->assertDatabaseHas('periode_pmb', ['is_active' => true]);
    }

    public function test_tarif_ukt_menghasilkan_matrix_penuh(): void
    {
        $jalurCount  = JalurMasuk::count();
        $prodiCount  = Prodi::count();
        $golonganCount = GolonganUkt::count();

        $expected = $jalurCount * $prodiCount * $golonganCount;

        $this->assertSame(3, $jalurCount);
        $this->assertSame(5, $prodiCount);
        $this->assertSame(5, $golonganCount);

        // Total baris tarif harus sama dengan jumlah kombinasi.
        $this->assertSame($expected, TarifUkt::count());
        $this->assertSame($expected, TarifUkt::select('periode_id', 'jalur_id', 'prodi_id', 'golongan_id')->distinct()->count());

        // Setiap kombinasi jalur x prodi x golongan harus benar-benar ada.
        $periode = PeriodePmb::active()->firstOrFail();

        foreach (JalurMasuk::all() as $jalur) {
            foreach (Prodi::all() as $prodi) {
                foreach (GolonganUkt::all() as $golongan) {
                    $this->assertDatabaseHas('tarif_ukt', [
                        'periode_id'  => $periode->id,
                        'jalur_id'    => $jalur->id,
                        'prodi_id'    => $prodi->id,
                        'golongan_id' => $golongan->id,
                    ]);
                }
            }
        }
    }

    public function test_urutan_tarif_snbp_terendah_snbt_menengah_mandiri_tertinggi(): void
    {
        $prodi = Prodi::where('kode', 'TGF')->firstOrFail();
        $periode = PeriodePmb::active()->firstOrFail();

        $ambil = fn (string $kodeJalur, string $kodeGolongan) => TarifUkt::query()
            ->where('periode_id', $periode->id)
            ->where('prodi_id', $prodi->id)
            ->where('golongan_id', GolonganUkt::where('kode', $kodeGolongan)->firstOrFail()->id)
            ->where('jalur_id', JalurMasuk::where('kode', $kodeJalur)->firstOrFail()->id)
            ->firstOrFail()
            ->nominal;

        // Golongan I: SNBP < SNBT < Mandiri
        $this->assertLessThan($ambil('SNBT', 'I'), $ambil('SNBP', 'I'));
        $this->assertLessThan($ambil('MANDIRI', 'I'), $ambil('SNBT', 'I'));

        // Within satu jalur: golongan I < V
        $this->assertLessThan($ambil('SNBP', 'V'), $ambil('SNBP', 'I'));
    }

    public function test_biaya_pendaftaran_hanya_untuk_jalur_mandiri(): void
    {
        $mandiri = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();
        $snbp    = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $snbt    = JalurMasuk::where('kode', 'SNBT')->firstOrFail();

        $this->assertTrue($mandiri->punya_biaya_pendaftaran);
        $this->assertFalse($snbp->punya_biaya_pendaftaran);
        $this->assertFalse($snbt->punya_biaya_pendaftaran);

        $this->assertSame(250000, BiayaPendaftaran::where('jalur_id', $mandiri->id)->value('nominal'));
        $this->assertNull(BiayaPendaftaran::where('jalur_id', $snbp->id)->value('nominal'));
        $this->assertNull(BiayaPendaftaran::where('jalur_id', $snbt->id)->value('nominal'));
    }

    public function test_mapping_dokumen_per_jalur(): void
    {
        $dokumen = fn (string $kode) => \App\Models\DokumenMaster::where('kode', $kode)->firstOrFail();

        $snbp = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $snbt = JalurMasuk::where('kode', 'SNBT')->firstOrFail();
        $mandiri = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();

        $this->assertEqualsCanonicalizing(
            ['RAPOR', 'PIAGAM'],
            $snbp->dokumen->pluck('kode')->all()
        );

        $this->assertEqualsCanonicalizing(
            ['RAPOR'],
            $snbt->dokumen->pluck('kode')->all()
        );

        $this->assertEqualsCanonicalizing(
            ['KTP', 'KK', 'FOTO', 'IJAZAH', 'RAPOR', 'PIAGAM'],
            $mandiri->dokumen->pluck('kode')->all()
        );

        $this->assertSame(9, DokumenJalur::count());
    }

    public function test_prodi_terhubung_ke_fakultas(): void
    {
        $fakultas = Fakultas::where('kode', 'TGP')->firstOrFail();

        $this->assertEqualsCanonicalizing(
            ['Teknik Grafika', 'Teknik Penerbitan'],
            $fakultas->prodi->pluck('nama')->all()
        );
    }

    public function test_halaman_publik_merender_data_dari_database(): void
    {
        $periode = PeriodePmb::active()->firstOrFail();
        $prodi = Prodi::where('kode', 'TGF')->firstOrFail();

        $this->get('/')->assertStatus(200)->assertSee($periode->nama);
        $this->get('/informasi-pmb')->assertStatus(200)->assertSee($periode->nama);
        $this->get('/program-studi')->assertStatus(200)->assertSee($prodi->nama);
    }

    public function test_halaman_admin_merender_data_dari_database(): void
    {
        $admin = \App\Models\User::where('email', 'admin@pmb.ac.id')->firstOrFail();

        $this->actingAs($admin);

        $this->get('/admin/dashboard')->assertStatus(200);
        $this->get('/admin/pendaftar')->assertStatus(200);
        $this->get('/admin/periode')->assertStatus(200)->assertSee(PeriodePmb::active()->firstOrFail()->nama);
        $this->get('/admin/program-studi')->assertStatus(200)->assertSee('Teknik Grafika');
        $this->get('/admin/periode/create')->assertStatus(200);
        $this->get('/admin/program-studi/create')->assertStatus(200);
    }
}
