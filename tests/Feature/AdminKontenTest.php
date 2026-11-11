<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Faq;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKontenTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('email', 'admin@pmb.ac.id')->firstOrFail();
    }

    public function test_crud_berita_dengan_slug_otomatis(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/berita', [
                'judul'        => 'Pendaftaran Gelombang 2 Resmi Dibuka',
                'image_url'    => 'https://contoh.test/gambar.jpg',
                'deskripsi'    => 'Pendaftaran gelombang 2 telah dibuka.',
                'author'       => 'Panitia PMB',
                'published_at' => now()->format('Y-m-d H:i:s'),
                'status'       => 'publish',
            ])
            ->assertRedirect(route('admin.berita.index'))
            ->assertSessionHas('success');

        $berita = Berita::where('judul', 'Pendaftaran Gelombang 2 Resmi Dibuka')->firstOrFail();

        // Slug dibuat otomatis dari judul.
        $this->assertSame('pendaftaran-gelombang-2-resmi-dibuka', $berita->slug);

        // Berita publish tampil di halaman publik.
        $this->get('/berita')->assertOk()->assertSee($berita->judul);
        $this->get('/berita/'.$berita->slug)->assertOk()->assertSee($berita->judul);

        // Edit.
        $this->actingAs($this->admin)
            ->put(route('admin.berita.update', $berita), [
                'judul'     => 'Pendaftaran Gelombang 2 Diperpanjang',
                'deskripsi' => 'Perpanjangan masa pendaftaran.',
                'author'    => 'Panitia PMB',
                'status'    => 'publish',
            ])
            ->assertRedirect(route('admin.berita.index'));

        $this->assertSame('pendaftaran-gelombang-2-diperpanjang', $berita->fresh()->slug);

        $berita->refresh();

        // Hapus.
        $this->actingAs($this->admin)
            ->delete(route('admin.berita.destroy', $berita))
            ->assertRedirect(route('admin.berita.index'));

        $this->assertDatabaseMissing('berita', ['id' => $berita->id]);
    }

    public function test_berita_draft_tidak_tampil_di_halaman_publik(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/berita', [
                'judul'     => 'Berita Draft Tidak Tampil',
                'deskripsi' => 'Masih disusun panitia.',
                'author'    => 'Panitia PMB',
                'status'    => 'draft',
            ]);

        $draft = Berita::where('judul', 'Berita Draft Tidak Tampil')->firstOrFail();

        $this->get('/berita')->assertOk()->assertDontSee('Berita Draft Tidak Tampil');
        $this->get('/berita/'.$draft->slug)->assertNotFound();
    }

    public function test_berita_publish_dengan_tanggal_mendatang_belum_tampil(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/berita', [
                'judul'        => 'Pengumuman Terjadwal',
                'deskripsi'    => 'Diumumkan minggu depan.',
                'author'       => 'Panitia PMB',
                'published_at' => now()->addWeek()->format('Y-m-d H:i:s'),
                'status'       => 'publish',
            ]);

        $terjadwal = Berita::where('judul', 'Pengumuman Terjadwal')->firstOrFail();

        $this->get('/berita')->assertOk()->assertDontSee('Pengumuman Terjadwal');
        $this->get('/berita/'.$terjadwal->slug)->assertNotFound();

        // Admin tetap melihat berita tersebut di daftar admin.
        $this->actingAs($this->admin)
            ->get('/admin/berita')
            ->assertOk()
            ->assertSee('Pengumuman Terjadwal');
    }

    public function test_slug_berita_unik(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/berita', [
                'judul'     => 'Judul Sama',
                'deskripsi' => 'Isi berita pertama.',
                'author'    => 'Panitia PMB',
                'status'    => 'draft',
            ]);

        $this->actingAs($this->admin)
            ->post('/admin/berita', [
                'judul'     => 'Judul Sama',
                'deskripsi' => 'Isi berita kedua.',
                'author'    => 'Panitia PMB',
                'status'    => 'draft',
            ]);

        $this->assertSame(2, Berita::where('slug', 'like', 'judul-sama%')->count());
    }

    public function test_crud_faq(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/faq', [
                'pertanyaan' => 'Berapa biaya pendaftaran jalur Mandiri?',
                'jawaban'    => 'Biaya pendaftaran jalur Mandiri adalah Rp250.000.',
                'kategori'   => 'Biaya',
                'urutan'     => 3,
            ])
            ->assertRedirect(route('admin.faq.index'))
            ->assertSessionHas('success');

        $faq = Faq::where('pertanyaan', 'Berapa biaya pendaftaran jalur Mandiri?')->firstOrFail();

        // Tampil di halaman publik.
        $this->get('/bantuan/faq')->assertOk()->assertSee($faq->pertanyaan);

        $this->actingAs($this->admin)
            ->put(route('admin.faq.update', $faq), [
                'pertanyaan' => 'Berapa biaya pendaftaran jalur Mandiri 2026?',
                'jawaban'    => 'Tetap Rp250.000.',
                'kategori'   => 'Biaya',
                'urutan'     => 4,
            ])
            ->assertRedirect(route('admin.faq.index'));

        $this->assertSame(4, $faq->fresh()->urutan);

        $this->actingAs($this->admin)
            ->delete(route('admin.faq.destroy', $faq))
            ->assertRedirect(route('admin.faq.index'));

        $this->assertDatabaseMissing('faq', ['id' => $faq->id]);
    }

    public function test_pengaturan_dapat_diubah_dan_tampil_di_halaman_publik(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/pengaturan')
            ->assertOk()
            ->assertSee('nama_kampus');

        $this->actingAs($this->admin)
            ->post('/admin/pengaturan', [
                'settings' => [
                    'nama_kampus' => 'Institut Teknologi Kreatif Jakarta',
                    'telepon_kampus' => '021-999888',
                ],
            ])
            ->assertRedirect(route('admin.pengaturan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pengaturan', [
            'key'   => 'nama_kampus',
            'value' => 'Institut Teknologi Kreatif Jakarta',
        ]);

        $this->get('/tentang-kampus')->assertOk()->assertSee('Institut Teknologi Kreatif Jakarta');

        // Pengaturan baru dapat ditambahkan.
        $this->actingAs($this->admin)
            ->post('/admin/pengaturan', [
                'key_baru'   => 'jam_pelayanan',
                'value_baru' => 'Senin - Jumat, 08.00 - 15.00 WIB',
            ]);

        $this->assertDatabaseHas('pengaturan', [
            'key'   => 'jam_pelayanan',
            'value' => 'Senin - Jumat, 08.00 - 15.00 WIB',
        ]);
    }

    public function test_statistik_dashboard_konsisten(): void
    {
        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::firstOrFail();

        // Pendaftar 1: menunggu verifikasi, belum bayar.
        $menunggu = $this->buatPendaftaran($periode, $jalur, $prodi, $golongan, 'menunggu_verifikasi');

        // Pendaftar 2: terverifikasi dan sudah bayar.
        $terverifikasi = $this->buatPendaftaran($periode, $jalur, $prodi, $golongan, 'terverifikasi');

        Pembayaran::create([
            'pendaftaran_id' => $terverifikasi->id,
            'jenis'          => 'ukt',
            'nominal'        => 4000000,
            'status'         => 'paid',
            'paid_at'        => now(),
        ]);

        // Pendaftar 3: draft.
        $this->buatPendaftaran($periode, $jalur, $prodi, $golongan, 'draft');

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertOk();

        $html = $response->getContent();

        $this->assertMatchesRegularExpression('/data-stat="Total Pendaftar"[^>]*>\s*3\s*</', $html);
        $this->assertMatchesRegularExpression('/data-stat="Menunggu Verifikasi"[^>]*>\s*1\s*</', $html);
        $this->assertMatchesRegularExpression('/data-stat="Terverifikasi"[^>]*>\s*1\s*</', $html);
        $this->assertMatchesRegularExpression('/data-stat="Sudah Bayar"[^>]*>\s*1\s*</', $html);
        $this->assertMatchesRegularExpression('/data-stat="Belum Bayar"[^>]*>\s*2\s*</', $html);
        $this->assertMatchesRegularExpression('/data-stat="Tagihan Menunggu"[^>]*>\s*0\s*</', $html);

        // Rincian status pendaftaran memuat draft.
        $this->assertStringContainsString('Draft', $html);

        // Pendaftar per jalur.
        $this->assertMatchesRegularExpression('/data-jalur-total="SNBP"[^>]*>\s*3\s*</', $html);

        // Daftar 10 pendaftar terbaru.
        $this->assertStringContainsString('10 Pendaftar Terbaru', $html);
    }

    public function test_daftar_pendaftar_terbaru_maksimal_10(): void
    {
        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::firstOrFail();

        for ($i = 0; $i < 12; $i++) {
            $this->buatPendaftaran($periode, $jalur, $prodi, $golongan, 'draft');
        }

        $html = $this->actingAs($this->admin)->get('/admin/dashboard')->assertOk()->getContent();

        // Baris tabel pendaftar = 10 (paling baru).
        $this->assertSame(10, substr_count($html, 'admin/pendaftar/'));
    }

    private function buatPendaftaran(
        PeriodePmb $periode,
        JalurMasuk $jalur,
        Prodi $prodi,
        GolonganUkt $golongan,
        string $status
    ): Pendaftaran {
        $user = User::factory()->create(['role' => 'pendaftar']);

        return Pendaftaran::create([
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => $status,
        ]);
    }
}
