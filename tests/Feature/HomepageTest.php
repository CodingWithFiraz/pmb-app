<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Fakultas;
use App\Models\JalurMasuk;
use App\Models\Pengaturan;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_hero_menampilkan_nama_kampus_dan_tagline_dari_pengaturan(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee(Pengaturan::get('nama_kampus'))
            ->assertSee(Pengaturan::get('tagline'))
            ->assertSee('Daftar Sekarang')
            ->assertSee('Lihat Panduan');
    }

    public function test_keunggulan_dan_alur_pendaftaran_tampil(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Mengapa Memilih')
            ->assertSee('Kurikulum Berbasis Industri')
            ->assertSee('Alur Pendaftaran')
            ->assertSee('Lengkapi Data Diri')
            ->assertSee('Pengumuman Kelulusan');
    }

    public function test_jalur_masuk_dan_ringkasan_biaya_dari_database(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Jalur Masuk')
            ->assertSee('Gratis')
            ->assertSee('Rp 250.000')
            ->assertSee('UKT mulai');

        foreach (JalurMasuk::orderBy('urutan')->get() as $jalur) {
            $response->assertSee($jalur->nama);
        }
    }

    public function test_fakultas_dan_prodi_ditampilkan_dari_database(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        foreach (Fakultas::all() as $fakultas) {
            $response->assertSee($fakultas->nama);
            $response->assertSee(route('public.fakultas.detail', $fakultas), false);
        }

        foreach (Prodi::all() as $prodi) {
            $response->assertSee($prodi->nama);
            $response->assertSee(route('public.prodi', $prodi), false);
        }
    }

    public function test_jadwal_penting_dari_periode_aktif(): void
    {
        $periode = PeriodePmb::active()->firstOrFail();

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Jadwal Penting')
            ->assertSee($periode->nama)
            ->assertSee($periode->tahun_akademik)
            ->assertSee($periode->tanggal_mulai->translatedFormat('d F Y'))
            ->assertSee($periode->tanggal_selesai->translatedFormat('d F Y'));
    }

    public function test_berita_terbaru_dibatasi_tiga_dan_berurutan(): void
    {
        $terlama = Berita::publish()->oldest('published_at')->firstOrFail();

        $baru = Berita::create([
            'judul'        => 'Pengumuman Terbaru Homepage',
            'slug'         => 'pengumuman-terbaru-homepage',
            'deskripsi'    => 'Berita paling baru untuk menguji batas tiga berita terbaru.',
            'author'       => 'Panitia PMB',
            'status'       => 'publish',
            'published_at' => now()->subMinute(),
        ]);

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Berita Terbaru')
            ->assertSee($baru->judul)
            ->assertDontSee($terlama->judul);
    }

    public function test_bantuan_cepat_menautkan_panduan_faq_dan_kontak(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Bantuan Cepat')
            ->assertSee(route('public.bantuan.panduan'), false)
            ->assertSee(route('public.bantuan.faq'), false)
            ->assertSee(route('public.bantuan.kontak'), false);
    }

    public function test_homepage_tetap_200_saat_tidak_ada_periode_aktif(): void
    {
        PeriodePmb::query()->update(['is_active' => false]);

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('belum ada periode pendaftaran yang aktif');
    }
}
