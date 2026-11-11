<?php

namespace Tests\Feature;

use App\Models\DokumenMaster;
use App\Models\DokumenPendaftar;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\TarifUkt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminVerifikasiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Storage::fake('local');

        $this->admin = User::where('email', 'admin@pmb.ac.id')->firstOrFail();
    }

    /**
     * Buat pendaftar + profil + pendaftaran pada periode aktif.
     *
     * @param  array<int, string>  $statusDokumen  Kode dokumen yang berstatus valid.
     */
    private function buatPendaftaran(string $kodeJalur = 'MANDIRI', string $status = 'menunggu_verifikasi', array $statusDokumen = ['RAPOR']): Pendaftaran
    {
        $user = User::factory()->create(['role' => 'pendaftar']);

        \App\Models\PendaftarProfile::create([
            'user_id'         => $user->id,
            'nik'             => '317101'.mt_rand(1000000, 9999999),
            'nisn'            => (string) mt_rand(1000000000, 9999999999),
            'tempat_lahir'    => 'Jakarta',
            'tanggal_lahir'   => '2006-06-06',
            'jenis_kelamin'   => 'L',
            'alamat'          => 'Jl. Contoh No. 1',
            'provinsi'        => 'DKI Jakarta',
            'kota'            => 'Jakarta Selatan',
            'asal_sekolah'    => 'SMA Negeri 1 Jakarta',
            'tahun_lulus'     => 2024,
            'nama_ortu'       => 'Budi Santoso',
            'pekerjaan_ortu'  => 'Karyawan',
            'penghasilan_ortu' => 4_500_000,
            'golongan_id'     => GolonganUkt::where('kode', 'II')->firstOrFail()->id,
        ]);

        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::where('kode', $kodeJalur)->firstOrFail();
        $prodi = Prodi::where('kode', 'TGF')->firstOrFail();
        $golongan = GolonganUkt::where('kode', 'II')->firstOrFail();

        $pendaftaran = Pendaftaran::create([
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'prodi_2_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => $status,
            'nomor_pendaftaran' => 'PMB-'.now()->year.'-'.str_pad((string) random_int(1, 999), 4, '0', STR_PAD_LEFT),
        ]);

        foreach ($statusDokumen as $kode) {
            $dokumen = DokumenMaster::where('kode', $kode)->firstOrFail();

            DokumenPendaftar::create([
                'pendaftaran_id' => $pendaftaran->id,
                'dokumen_id'     => $dokumen->id,
                'file_path'      => 'dokumen/'.$pendaftaran->id.'/'.strtolower($kode).'.pdf',
                'status'         => 'valid',
            ]);
        }

        return $pendaftaran->fresh();
    }

    public function test_daftar_pendaftar_menampilkan_filter_periode_jalur_dan_status(): void
    {
        $mandiri = $this->buatPendaftaran('MANDIRI', 'menunggu_verifikasi', ['RAPOR']);
        $snbp = $this->buatPendaftaran('SNBP', 'terverifikasi', ['RAPOR']);

        $this->actingAs($this->admin)
            ->get('/admin/pendaftar')
            ->assertOk()
            ->assertSee($mandiri->user->name)
            ->assertSee($snbp->user->name);

        // Filter status.
        $this->actingAs($this->admin)
            ->get('/admin/pendaftar?status=terverifikasi')
            ->assertOk()
            ->assertSee($snbp->user->name)
            ->assertDontSee($mandiri->user->name);

        // Filter jalur.
        $this->actingAs($this->admin)
            ->get('/admin/pendaftar?jalur_id='.$mandiri->jalur_id)
            ->assertOk()
            ->assertSee($mandiri->user->name)
            ->assertDontSee($snbp->user->name);

        // Filter periode.
        $this->actingAs($this->admin)
            ->get('/admin/pendaftar?periode_id='.$mandiri->periode_id)
            ->assertOk()
            ->assertSee($mandiri->user->name)
            ->assertSee($snbp->user->name);

        $this->actingAs($this->admin)
            ->get('/admin/pendaftar?periode_id=99999')
            ->assertOk()
            ->assertDontSee($mandiri->user->name);
    }

    public function test_detail_pendaftar_menampilkan_profil_dan_dokumen(): void
    {
        $pendaftaran = $this->buatPendaftaran('MANDIRI', 'menunggu_verifikasi', ['RAPOR']);

        $this->actingAs($this->admin)
            ->get(route('admin.pendaftaran.show', $pendaftaran))
            ->assertOk()
            ->assertSee($pendaftaran->user->name)
            ->assertSee($pendaftaran->user->profile->nik)
            ->assertSee('Teknik Grafika')
            ->assertSee('Rapor SMA/SMK')
            ->assertSee('Kartu Tanda Penduduk (KTP)')
            ->assertSee('Unduh');
    }

    public function test_admin_dapat_menandai_dokumen_valid(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP', 'menunggu_verifikasi', []);

        $dokumen = DokumenMaster::where('kode', 'RAPOR')->firstOrFail();

        $this->actingAs($this->admin)
            ->post(route('admin.dokumen.status', $this->upload($pendaftaran, $dokumen)), [
                'status'  => 'valid',
                'catatan' => 'Berkas terbaca dan sesuai.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('dokumen_pendaftar', [
            'pendaftaran_id' => $pendaftaran->id,
            'dokumen_id'     => $dokumen->id,
            'status'         => 'valid',
        ]);
    }

    public function test_admin_menolak_dokumen_otomatis_menolak_pendaftaran(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP', 'menunggu_verifikasi', []);
        $dokumen = DokumenMaster::where('kode', 'RAPOR')->firstOrFail();

        $upload = $this->upload($pendaftaran, $dokumen);

        $this->actingAs($this->admin)
            ->post(route('admin.dokumen.status', $upload), [
                'status'  => 'ditolak',
                'catatan' => 'Rapor tidak lengkap.',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('dokumen_pendaftar', [
            'id'     => $upload->id,
            'status' => 'ditolak',
        ]);

        $this->assertDatabaseHas('pendaftaran', [
            'id'         => $pendaftaran->id,
            'status'     => 'ditolak',
            'catatan_admin' => 'Dokumen Rapor SMA/SMK ditolak: Rapor tidak lengkap.',
        ]);
    }

    public function test_verifikasi_pendaftar_mandiri_membuat_dua_tagihan(): void
    {
        $pendaftaran = $this->buatPendaftaran('MANDIRI', 'menunggu_verifikasi', ['RAPOR', 'KTP', 'KK', 'FOTO', 'IJAZAH', 'PIAGAM']);

        $uktHarapan = TarifUkt::where('periode_id', $pendaftaran->periode_id)
            ->where('jalur_id', $pendaftaran->jalur_id)
            ->where('prodi_id', $pendaftaran->prodi_1_id)
            ->where('golongan_id', $pendaftaran->golongan_id)
            ->value('nominal');

        $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.verifikasi', $pendaftaran))
            ->assertRedirect()
            ->assertSessionHas('success');

        $pendaftaran->refresh();

        $this->assertSame('terverifikasi', $pendaftaran->status);
        $this->assertSame($this->admin->id, $pendaftaran->verified_by);
        $this->assertNotNull($pendaftaran->verified_at);

        // Dua tagihan: pendaftaran + UKT.
        $this->assertSame(2, $pendaftaran->pembayaran()->count());

        $this->assertDatabaseHas('pembayaran', [
            'pendaftaran_id' => $pendaftaran->id,
            'jenis'          => 'pendaftaran',
            'nominal'        => 250000,
            'status'         => 'pending',
        ]);

        $this->assertDatabaseHas('pembayaran', [
            'pendaftaran_id' => $pendaftaran->id,
            'jenis'          => 'ukt',
            'nominal'        => $uktHarapan,
            'status'         => 'pending',
        ]);
    }

    public function test_verifikasi_pendaftar_snbp_membuat_satu_tagihan_ukt(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP', 'menunggu_verifikasi', ['RAPOR']);

        $uktHarapan = TarifUkt::where('periode_id', $pendaftaran->periode_id)
            ->where('jalur_id', $pendaftaran->jalur_id)
            ->where('prodi_id', $pendaftaran->prodi_1_id)
            ->where('golongan_id', $pendaftaran->golongan_id)
            ->value('nominal');

        $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.verifikasi', $pendaftaran))
            ->assertSessionHas('success');

        // Jalur SNBP tidak mewajibkan biaya pendaftaran, sehingga hanya tagihan UKT.
        $this->assertSame(1, $pendaftaran->pembayaran()->count());

        $this->assertDatabaseHas('pembayaran', [
            'pendaftaran_id' => $pendaftaran->id,
            'jenis'          => 'ukt',
            'nominal'        => $uktHarapan,
            'status'         => 'pending',
        ]);

        $this->assertDatabaseMissing('pembayaran', [
            'pendaftaran_id' => $pendaftaran->id,
            'jenis'          => 'pendaftaran',
        ]);
    }

    public function test_verifikasi_ditolak_bila_dokumen_wajib_belum_valid(): void
    {
        $pendaftaran = $this->buatPendaftaran('MANDIRI', 'menunggu_verifikasi', []);

        $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.verifikasi', $pendaftaran))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'menunggu_verifikasi',
        ]);

        $this->assertSame(0, $pendaftaran->pembayaran()->count());
    }

    public function test_tagihan_tidak_berduplikasi_saat_verifikasi_dilakukan_ulang(): void
    {
        $pendaftaran = $this->buatPendaftaran('MANDIRI', 'menunggu_verifikasi', ['RAPOR', 'KTP', 'KK', 'FOTO', 'IJAZAH', 'PIAGAM']);

        $this->actingAs($this->admin)->post(route('admin.pendaftaran.verifikasi', $pendaftaran));

        $this->actingAs($this->admin)->post(route('admin.pendaftaran.verifikasi', $pendaftaran));

        $this->assertSame(2, $pendaftaran->pembayaran()->count());
    }

    public function test_tolak_pendaftar_menyimpan_catatan(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP', 'menunggu_verifikasi', ['RAPOR']);

        $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.tolak', $pendaftaran), [
                'catatan' => 'Data profil tidak sesuai dokumen.',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftaran', [
            'id'            => $pendaftaran->id,
            'status'        => 'ditolak',
            'catatan_admin' => 'Data profil tidak sesuai dokumen.',
        ]);
    }

    public function test_pendaftar_dapat_revisi_dan_mengajukan_ulang_setelah_ditolak(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP', 'menunggu_verifikasi', []);
        $user = $pendaftaran->user;

        // Admin menolak pendaftaran.
        $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.tolak', $pendaftaran), [
                'catatan' => 'Rapor belum diunggah.',
            ]);

        // Pendaftar melihat catatan dan dapat mengunggah ulang.
        $this->actingAs($user)
            ->get('/pendaftar/dokumen')
            ->assertOk()
            ->assertSee('Rapor belum diunggah.');

        $dokumen = DokumenMaster::where('kode', 'RAPOR')->firstOrFail();

        $this->actingAs($user)
            ->post(route('pendaftar.dokumen.upload', $dokumen), [
                'file' => \Illuminate\Http\UploadedFile::fake()->create('rapor.pdf', 100, 'application/pdf'),
            ])
            ->assertSessionHas('success');

        // Pendaftar mengajukan ulang.
        $this->actingAs($user)
            ->post(route('pendaftar.dokumen.verifikasi'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'menunggu_verifikasi',
        ]);

        // Admin memvalidasi dokumen lalu memverifikasi pendaftaran.
        $upload = $pendaftaran->dokumen()->where('dokumen_id', $dokumen->id)->firstOrFail();

        $this->actingAs($this->admin)
            ->post(route('admin.dokumen.status', $upload), ['status' => 'valid'])
            ->assertSessionHas('success');

        $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.verifikasi', $pendaftaran))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'terverifikasi',
        ]);
    }

    /**
     * Buat record dokumen pendaftar beserta berkasnya.
     */
    private function upload(Pendaftaran $pendaftaran, DokumenMaster $dokumen): DokumenPendaftar
    {
        $path = 'dokumen/'.$pendaftaran->id.'/'.strtolower($dokumen->kode).'.pdf';

        Storage::disk('local')->put($path, 'dummy');

        return DokumenPendaftar::updateOrCreate(
            [
                'pendaftaran_id' => $pendaftaran->id,
                'dokumen_id'     => $dokumen->id,
            ],
            [
                'file_path' => $path,
                'status'    => 'menunggu',
            ]
        );
    }
}
