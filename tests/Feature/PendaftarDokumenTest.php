<?php

namespace Tests\Feature;

use App\Models\DokumenMaster;
use App\Models\DokumenPendaftar;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PendaftarDokumenTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Storage::fake('local');
    }

    private function pendaftar(): \App\Models\User
    {
        return \App\Models\User::where('email', 'pendaftar@pmb.ac.id')->firstOrFail();
    }

    /**
     * Buat pendaftaran berstatus draft untuk pendaftar dengan jalur tertentu.
     */
    private function buatPendaftaran(string $kodeJalur = 'SNBP'): Pendaftaran
    {
        $user = $this->pendaftar();
        $periode = \App\Models\PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::where('kode', $kodeJalur)->firstOrFail();
        $prodi = \App\Models\Prodi::firstOrFail();
        $golongan = GolonganUkt::where('kode', 'II')->firstOrFail();

        return Pendaftaran::create([
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'prodi_2_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => 'draft',
        ]);
    }

    public function test_halaman_dokumen_menampilkan_daftar_dokumen_sesuai_jalur(): void
    {
        $this->buatPendaftaran('SNBP');

        $this->actingAs($this->pendaftar())
            ->get('/pendaftar/dokumen')
            ->assertOk()
            ->assertSee('Dokumen Pendaftaran')
            ->assertSee('Rapor SMA/SMK')
            ->assertSee('Piagam Prestasi')
            ->assertDontSee('Kartu Tanda Penduduk (KTP)');
    }

    public function test_ganti_jalur_di_halaman_pilihan_mengubah_daftar_dokumen(): void
    {
        $this->buatPendaftaran('SNBP');

        $user = $this->pendaftar();

        $this->actingAs($user)
            ->get('/pendaftar/dokumen')
            ->assertSee('Rapor SMA/SMK')
            ->assertDontSee('Kartu Tanda Penduduk (KTP)');

        // Ganti jalur ke Mandiri lewat alur FASE 4.
        $jalurMandiri = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();

        $this->actingAs($user)
            ->post('/pendaftar/pilihan', [
                'jalur_id'    => $jalurMandiri->id,
                'prodi_1_id'  => \App\Models\Prodi::firstOrFail()->id,
                'golongan_id' => GolonganUkt::where('kode', 'II')->firstOrFail()->id,
            ])
            ->assertRedirect(route('pendaftar.pilihan'));

        $this->actingAs($user)
            ->get('/pendaftar/dokumen')
            ->assertOk()
            ->assertSee('Kartu Tanda Penduduk (KTP)')
            ->assertSee('Kartu Keluarga (KK)')
            ->assertSee('Ijazah / Surat Keterangan Lulus');
    }

    public function test_upload_dokumen_disimpan_dengan_status_menunggu(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP');
        $dokumen = DokumenMaster::where('kode', 'RAPOR')->firstOrFail();

        $this->actingAs($this->pendaftar())
            ->from(route('pendaftar.dokumen'))
            ->post(route('pendaftar.dokumen.upload', $dokumen), [
                'file' => UploadedFile::fake()->create('rapor.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect(route('pendaftar.dokumen'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('dokumen_pendaftar', [
            'pendaftaran_id' => $pendaftaran->id,
            'dokumen_id'     => $dokumen->id,
            'status'         => 'menunggu',
        ]);

        $upload = DokumenPendaftar::where('pendaftaran_id', $pendaftaran->id)
            ->where('dokumen_id', $dokumen->id)
            ->firstOrFail();

        Storage::disk('local')->assertExists($upload->file_path);
    }

    public function test_upload_menolak_format_di_luar_jpg_png_pdf(): void
    {
        $this->buatPendaftaran('SNBP');
        $dokumen = DokumenMaster::where('kode', 'RAPOR')->firstOrFail();

        $this->actingAs($this->pendaftar())
            ->from(route('pendaftar.dokumen'))
            ->post(route('pendaftar.dokumen.upload', $dokumen), [
                'file' => UploadedFile::fake()->create('catatan.txt', 20, 'text/plain'),
            ])
            ->assertSessionHasErrors('file');

        $this->assertDatabaseCount('dokumen_pendaftar', 0);
    }

    public function test_upload_menolak_ukuran_melebihi_batas_dokumen(): void
    {
        $this->buatPendaftaran('SNBP');
        $dokumen = DokumenMaster::where('kode', 'RAPOR')->firstOrFail();

        $this->actingAs($this->pendaftar())
            ->from(route('pendaftar.dokumen'))
            ->post(route('pendaftar.dokumen.upload', $dokumen), [
                'file' => UploadedFile::fake()->create('rapor.pdf', $dokumen->max_size + 500, 'application/pdf'),
            ])
            ->assertSessionHasErrors('file');

        $this->assertDatabaseCount('dokumen_pendaftar', 0);
    }

    public function test_dokumen_di_luar_syarat_jalur_tidak_bisa_diunggah(): void
    {
        $this->buatPendaftaran('SNBP');
        $dokumen = DokumenMaster::where('kode', 'KTP')->firstOrFail();

        $this->actingAs($this->pendaftar())
            ->from(route('pendaftar.dokumen'))
            ->post(route('pendaftar.dokumen.upload', $dokumen), [
                'file' => UploadedFile::fake()->create('ktp.pdf', 50, 'application/pdf'),
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('dokumen_pendaftar', 0);
    }

    public function test_ajaxukan_verifikasi_tanpa_dokumen_lengkap_ditolak(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP');

        $this->actingAs($this->pendaftar())
            ->from(route('pendaftar.dokumen'))
            ->post(route('pendaftar.dokumen.verifikasi'))
            ->assertRedirect(route('pendaftar.dokumen'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pendaftaran', [
            'id'                => $pendaftaran->id,
            'status'            => 'draft',
            'nomor_pendaftaran' => null,
        ]);
    }

    public function test_ajaxukan_verifikasi_dengan_dokumen_lengkap_berhasil(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP');
        $user = $this->pendaftar();

        // SNBP: RAPOR (wajib) dan PIAGAM (opsional).
        foreach (['RAPOR', 'PIAGAM'] as $kode) {
            $dokumen = DokumenMaster::where('kode', $kode)->firstOrFail();

            $this->actingAs($user)
                ->post(route('pendaftar.dokumen.upload', $dokumen), [
                    'file' => UploadedFile::fake()->create(strtolower($kode).'.pdf', 100, 'application/pdf'),
                ]);
        }

        $this->actingAs($user)
            ->post(route('pendaftar.dokumen.verifikasi'))
            ->assertRedirect(route('pendaftar.dokumen'))
            ->assertSessionHas('success');

        $pendaftaran->refresh();

        $this->assertSame('menunggu_verifikasi', $pendaftaran->status);
        $this->assertMatchesRegularExpression('/^PMB-\d{4}-\d{4}$/', $pendaftaran->nomor_pendaftaran);
        $this->assertSame('PMB-'.now()->year.'-0001', $pendaftaran->nomor_pendaftaran);
    }

    public function test_setelah_diajukan_jalur_dan_prodi_terkunci(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP');
        $user = $this->pendaftar();
        $jalurAwal = $pendaftaran->jalur_id;

        $dokumen = DokumenMaster::where('kode', 'RAPOR')->firstOrFail();

        $this->actingAs($user)->post(route('pendaftar.dokumen.upload', $dokumen), [
            'file' => UploadedFile::fake()->create('rapor.pdf', 100, 'application/pdf'),
        ]);

        $this->actingAs($user)->post(route('pendaftar.dokumen.verifikasi'));

        // Jalur tidak bisa diganti lagi.
        $jalurMandiri = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();

        $this->actingAs($user)
            ->post('/pendaftar/pilihan', [
                'jalur_id'    => $jalurMandiri->id,
                'prodi_1_id'  => \App\Models\Prodi::firstOrFail()->id,
                'golongan_id' => GolonganUkt::where('kode', 'II')->firstOrFail()->id,
            ])
            ->assertRedirect(route('pendaftar.pilihan'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pendaftaran', [
            'id'       => $pendaftaran->id,
            'jalur_id' => $jalurAwal,
            'status'   => 'menunggu_verifikasi',
        ]);

        $this->actingAs($user)
            ->get('/pendaftar/pilihan')
            ->assertOk()
            ->assertSee('dikunci');
    }

    public function test_halaman_dokumen_menampilkan_catatan_admin(): void
    {
        $pendaftaran = $this->buatPendaftaran('SNBP');
        $dokumen = DokumenMaster::where('kode', 'RAPOR')->firstOrFail();

        DokumenPendaftar::create([
            'pendaftaran_id' => $pendaftaran->id,
            'dokumen_id'     => $dokumen->id,
            'file_path'      => 'dokumen/'.$pendaftaran->id.'/rapor.pdf',
            'status'         => 'ditolak',
            'catatan_admin'  => 'Berkas tidak terbaca, silakan unggah ulang.',
        ]);

        $this->actingAs($this->pendaftar())
            ->get('/pendaftar/dokumen')
            ->assertOk()
            ->assertSee('Ditolak')
            ->assertSee('Berkas tidak terbaca, silakan unggah ulang.');
    }

    public function test_nomor_pendaftaran_berurutan(): void
    {
        $pertama = $this->buatPendaftaran('SNBP');
        $user = $this->pendaftar();
        $dokumen = DokumenMaster::where('kode', 'RAPOR')->firstOrFail();

        $this->actingAs($user)->post(route('pendaftar.dokumen.upload', $dokumen), [
            'file' => UploadedFile::fake()->create('rapor.pdf', 100, 'application/pdf'),
        ]);

        $this->actingAs($user)->post(route('pendaftar.dokumen.verifikasi'));

        $this->assertSame('PMB-'.now()->year.'-0001', $pertama->fresh()->nomor_pendaftaran);

        // Pendaftar kedua mendapat nomor berikutnya.
        $userKedua = \App\Models\User::factory()->create(['role' => 'pendaftar']);

        \App\Models\PendaftarProfile::create([
            'user_id'       => $userKedua->id,
            'nik'           => '3171012305980077',
            'nisn'          => '0123456777',
            'tempat_lahir'  => 'Jakarta',
            'tanggal_lahir' => '2006-05-05',
            'jenis_kelamin' => 'P',
            'alamat'        => 'Jl. Dua No. 2',
            'provinsi'      => 'DKI Jakarta',
            'kota'          => 'Jakarta Barat',
            'asal_sekolah'  => 'SMA Negeri 3 Jakarta',
            'tahun_lulus'   => 2024,
            'nama_ortu'     => 'Budi Kedua',
            'pekerjaan_ortu' => 'Karyawan',
            'penghasilan_ortu' => 3_000_000,
            'golongan_id'   => GolonganUkt::where('kode', 'II')->firstOrFail()->id,
        ]);

        $pendaftaranKedua = Pendaftaran::create([
            'user_id'     => $userKedua->id,
            'periode_id'  => $pertama->periode_id,
            'jalur_id'    => $pertama->jalur_id,
            'prodi_1_id'  => $pertama->prodi_1_id,
            'golongan_id' => $pertama->golongan_id,
            'status'      => 'draft',
        ]);

        DokumenPendaftar::create([
            'pendaftaran_id' => $pendaftaranKedua->id,
            'dokumen_id'     => $dokumen->id,
            'file_path'      => 'dokumen/'.$pendaftaranKedua->id.'/rapor.pdf',
            'status'         => 'menunggu',
        ]);

        $this->actingAs($userKedua)->post(route('pendaftar.dokumen.verifikasi'));

        $this->assertSame('PMB-'.now()->year.'-0002', $pendaftaranKedua->fresh()->nomor_pendaftaran);
    }
}
