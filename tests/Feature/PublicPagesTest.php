<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Fakultas;
use App\Models\Faq;
use App\Models\Prodi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function publicUrlProvider(): array
    {
        return [
            'beranda'          => ['/'],
            'tentang kampus'   => ['/tentang-kampus'],
            'fakultas'         => ['/fakultas'],
            'program studi'    => ['/program-studi'],
            'informasi pmb'    => ['/informasi-pmb'],
            'berita'           => ['/berita'],
            'panduan'          => ['/bantuan/panduan'],
            'faq'              => ['/bantuan/faq'],
            'kontak'           => ['/bantuan/kontak'],
        ];
    }

    #[DataProvider('publicUrlProvider')]
    public function test_semua_halaman_publik_mengembalikan_200(string $url): void
    {
        $this->get($url)->assertStatus(200);
    }

    public function test_halaman_detail_fakultas_dan_prodi_mengembalikan_200(): void
    {
        $fakultas = Fakultas::firstOrFail();
        $prodi = Prodi::firstOrFail();

        $this->get('/fakultas/' . $fakultas->slug)->assertStatus(200)->assertSee($fakultas->nama);
        $this->get('/prodi/' . $prodi->slug)->assertStatus(200)->assertSee($prodi->nama);
    }

    public function test_berita_publish_ditampilkan_dari_seeder(): void
    {
        $this->get('/berita')->assertStatus(200);

        $berita = Berita::publish()->firstOrFail();

        $this->get('/berita')->assertSee($berita->judul);
        $this->get('/berita/' . $berita->slug)->assertStatus(200)->assertSee($berita->judul);
    }

    public function test_berita_draft_tidak_bisa_diakses(): void
    {
        $draft = Berita::create([
            'judul'     => 'Berita Draft',
            'slug'      => 'berita-draft-tidak-terbit',
            'deskripsi' => 'Belum dipublikasikan.',
            'author'    => 'Panitia PMB',
            'status'    => 'draft',
        ]);

        $this->get('/berita/' . $draft->slug)->assertStatus(404);
    }

    public function test_faq_dari_seeder_tampil_di_halaman_faq(): void
    {
        $faq = Faq::firstOrFail();

        $this->get('/bantuan/faq')->assertStatus(200)->assertSee($faq->pertanyaan);
    }

    public function test_halaman_informasi_pmb_menampilkan_tarif_ukt_dan_biaya(): void
    {
        $response = $this->get('/informasi-pmb');

        $response->assertStatus(200)
            ->assertSee('Biaya UKT')
            ->assertSee('Jalur Masuk')
            ->assertSee('SNBP')
            ->assertSee('Rp 250.000');
    }

    public function test_halaman_tentang_kampus_menampilkan_visi_misi_dan_sambutan(): void
    {
        $this->get('/tentang-kampus')
            ->assertStatus(200)
            ->assertSee('Visi')
            ->assertSee('Misi')
            ->assertSee('Sambutan')
            ->assertSee('Dr. Hendra Wijaya, M.M.');
    }

    public function test_halaman_kontak_menampilkan_embed_peta_dari_pengaturan(): void
    {
        $this->get('/bantuan/kontak')
            ->assertStatus(200)
            ->assertSee('<iframe', false)
            ->assertSee('google.com/maps', false);
    }

    public function test_navbar_publik_menautkan_semua_halaman(): void
    {
        $response = $this->get('/');

        $response->assertSee(route('public.tentang-kampus'), false)
            ->assertSee(route('public.fakultas'), false)
            ->assertSee(route('public.programs'), false)
            ->assertSee(route('public.informasi-pmb'), false)
            ->assertSee(route('public.berita'), false)
            ->assertSee(route('public.bantuan.panduan'), false)
            ->assertSee(route('public.bantuan.faq'), false)
            ->assertSee(route('public.bantuan.kontak'), false);
    }
}
