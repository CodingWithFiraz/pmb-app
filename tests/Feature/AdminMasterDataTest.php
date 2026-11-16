<?php

namespace Tests\Feature;

use App\Models\BiayaPendaftaran;
use App\Models\DokumenJalur;
use App\Models\DokumenMaster;
use App\Models\Fakultas;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\TarifUkt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMasterDataTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('email', 'admin@pmb.ac.id')->firstOrFail();
    }

    public function test_crud_fakultas(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/fakultas', [
                'kode'      => 'TEK',
                'nama'      => 'Fakultas Teknik Elektro',
                'deskripsi' => 'Fakultas teknik elektro.',
            ])
            ->assertRedirect(route('admin.fakultas.index'))
            ->assertSessionHas('success');

        $fakultas = Fakultas::where('kode', 'TEK')->firstOrFail();
        $this->assertSame('teknik-elektro', $fakultas->slug);

        $this->actingAs($this->admin)
            ->get(route('admin.fakultas.edit', $fakultas))
            ->assertOk();

        $this->actingAs($this->admin)
            ->put(route('admin.fakultas.update', $fakultas), [
                'kode' => 'TEK',
                'nama' => 'Fakultas Teknik Elektro dan Telematika',
            ])
            ->assertRedirect(route('admin.fakultas.index'));

        $this->assertSame('teknik-elektro-dan-telematika', $fakultas->fresh()->slug);

        $fakultas->refresh();

        $this->actingAs($this->admin)
            ->delete(route('admin.fakultas.destroy', $fakultas))
            ->assertRedirect(route('admin.fakultas.index'));

        $this->assertDatabaseMissing('fakultas', ['kode' => 'TEK']);
    }

    public function test_crud_prodi_dengan_relasi_fakultas(): void
    {
        $fakultas = Fakultas::where('kode', 'TGP')->firstOrFail();

        $this->actingAs($this->admin)
            ->post('/admin/program-studi', [
                'kode'        => 'TKL',
                'nama'        => 'Teknik Komputer',
                'fakultas_id' => $fakultas->id,
                'jenjang'     => 'S1',
                'kuota'       => 100,
            ])
            ->assertRedirect(route('admin.program-studi.index'));

        $prodi = Prodi::where('kode', 'TKL')->firstOrFail();
        $this->assertSame($fakultas->id, $prodi->fakultas_id);
        $this->assertSame('teknik-komputer', $prodi->slug);

        $this->actingAs($this->admin)
            ->put(route('admin.program-studi.update', $prodi), [
                'kode'        => 'TKL',
                'nama'        => 'Teknik Komputer dan Jaringan',
                'fakultas_id' => $fakultas->id,
                'jenjang'     => 'S1',
                'kuota'       => 120,
            ])
            ->assertRedirect(route('admin.program-studi.index'));

        $this->assertSame('teknik-komputer-dan-jaringan', $prodi->fresh()->slug);

        $prodi->refresh();

        $this->actingAs($this->admin)
            ->delete(route('admin.program-studi.destroy', $prodi));

        $this->assertDatabaseMissing('prodi', ['kode' => 'TKL']);
    }

    public function test_crud_jalur_masuk(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/jalur-masuk', [
                'kode'                    => 'UB',
                'nama'                    => 'Jalur Ujiiox',
                'deskripsi'               => 'Jalur khusus.',
                'urutan'                  => 4,
                'punya_biaya_pendaftaran' => '1',
            ])
            ->assertRedirect(route('admin.jalur-masuk.index'));

        $jalur = JalurMasuk::where('kode', 'UB')->firstOrFail();
        $this->assertTrue($jalur->punya_biaya_pendaftaran);
        $this->assertSame(4, $jalur->urutan);

        $this->actingAs($this->admin)
            ->put(route('admin.jalur-masuk.update', $jalur), [
                'kode'    => 'UB',
                'nama'    => 'Jalur Ujiiox',
                'urutan'  => 5,
            ])
            ->assertRedirect(route('admin.jalur-masuk.index'));

        $this->assertFalse($jalur->fresh()->punya_biaya_pendaftaran);

        $this->actingAs($this->admin)
            ->delete(route('admin.jalur-masuk.destroy', $jalur));

        $this->assertDatabaseMissing('jalur_masuk', ['kode' => 'UB']);
    }

    public function test_crud_golongan_ukt(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/golongan-ukt', [
                'kode'      => 'VI',
                'nama'      => 'Golongan VI',
                'deskripsi' => 'Golongan tambahan.',
            ])
            ->assertRedirect(route('admin.golongan-ukt.index'));

        $golongan = GolonganUkt::where('kode', 'VI')->firstOrFail();

        $this->actingAs($this->admin)
            ->put(route('admin.golongan-ukt.update', $golongan), [
                'kode' => 'VI',
                'nama' => 'Golongan VI (Revisi)',
            ])
            ->assertRedirect(route('admin.golongan-ukt.index'));

        $this->assertSame('Golongan VI (Revisi)', $golongan->fresh()->nama);

        $this->actingAs($this->admin)
            ->delete(route('admin.golongan-ukt.destroy', $golongan));

        $this->assertDatabaseMissing('golongan_ukt', ['kode' => 'VI']);
    }

    public function test_hanya_satu_periode_aktif(): void
    {
        $aktif = PeriodePmb::active()->firstOrFail();

        // Buat periode baru dalam status aktif.
        $this->actingAs($this->admin)
            ->post('/admin/periode', [
                'nama'            => 'PMB Gelombang 2',
                'tahun_akademik' => '2026/2027',
                'tanggal_mulai'   => '2026-07-01',
                'tanggal_selesai' => '2026-12-31',
                'is_active'       => '1',
            ])
            ->assertRedirect(route('admin.periode.index'));

        $baru = PeriodePmb::where('tahun_akademik', '2026/2027')->firstOrFail();

        $this->assertTrue($baru->is_active);
        $this->assertFalse($aktif->fresh()->is_active);
        $this->assertSame(1, PeriodePmb::where('is_active', true)->count());

        // Toggle: aktifkan kembali periode lama, periode baru otomatis nonaktif.
        $this->actingAs($this->admin)
            ->post(route('admin.periode.toggle', $aktif))
            ->assertRedirect(route('admin.periode.index'));

        $this->assertTrue($aktif->fresh()->is_active);
        $this->assertFalse($baru->fresh()->is_active);
        $this->assertSame(1, PeriodePmb::where('is_active', true)->count());

        // Menonaktifkan periode yang sedang aktif.
        $this->actingAs($this->admin)->post(route('admin.periode.toggle', $aktif));

        $this->assertFalse($aktif->fresh()->is_active);
        $this->assertSame(0, PeriodePmb::where('is_active', true)->count());
    }

    public function test_crud_biaya_pendaftaran(): void
    {
        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();

        $this->actingAs($this->admin)
            ->post('/admin/biaya-pendaftaran', [
                'periode_id' => $periode->id,
                'jalur_id'   => $jalur->id,
                'nominal'    => 300000,
            ])
            ->assertRedirect(route('admin.biaya-pendaftaran.index'));

        $biaya = BiayaPendaftaran::where('periode_id', $periode->id)
            ->where('jalur_id', $jalur->id)
            ->firstOrFail();

        $this->assertSame(300000, $biaya->nominal);

        $this->actingAs($this->admin)
            ->put(route('admin.biaya-pendaftaran.update', $biaya), [
                'periode_id' => $periode->id,
                'jalur_id'   => $jalur->id,
                'nominal'    => 350000,
            ])
            ->assertRedirect(route('admin.biaya-pendaftaran.index'));

        $this->assertSame(350000, $biaya->fresh()->nominal);

        $this->actingAs($this->admin)
            ->delete(route('admin.biaya-pendaftaran.destroy', $biaya));

        $this->assertDatabaseMissing('biaya_pendaftaran', ['id' => $biaya->id]);
    }

    public function test_crud_dokumen_master_dan_mapping_jalur(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/dokumen', [
                'kode'      => 'IJAZAH_SMA',
                'nama'      => 'Ijazah SMA',
                'tipe_file' => 'pdf',
                'max_size'  => 2048,
                'is_wajib'  => '1',
            ])
            ->assertRedirect(route('admin.dokumen.index'));

        $dokumen = DokumenMaster::where('kode', 'IJAZAH_SMA')->firstOrFail();
        $this->assertTrue($dokumen->is_wajib);

        $this->actingAs($this->admin)
            ->put(route('admin.dokumen.update', $dokumen), [
                'kode'      => 'IJAZAH_SMA',
                'nama'      => 'Ijazah SMA / Surat Keterangan Lulus',
                'tipe_file' => 'pdf',
                'max_size'  => 4096,
            ])
            ->assertRedirect(route('admin.dokumen.index'));

        $this->assertSame(4096, $dokumen->fresh()->max_size);

        // Mapping ke jalur SNBP saja.
        $snbp = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $mandiri = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();

        $this->actingAs($this->admin)
            ->get(route('admin.dokumen.mapping', $dokumen))
            ->assertOk()
            ->assertSee('Mapping Dokumen');

        $this->actingAs($this->admin)
            ->post(route('admin.dokumen.mapping.simpan', $dokumen), [
                'jalur' => [$snbp->id],
            ])
            ->assertRedirect(route('admin.dokumen.index'));

        $this->assertDatabaseHas('dokumen_jalur', [
            'dokumen_id' => $dokumen->id,
            'jalur_id'   => $snbp->id,
        ]);

        $this->assertDatabaseMissing('dokumen_jalur', [
            'dokumen_id' => $dokumen->id,
            'jalur_id'   => $mandiri->id,
        ]);

        // Menambahkan jalur lain langsung menambah mapping.
        $this->actingAs($this->admin)
            ->post(route('admin.dokumen.mapping.simpan', $dokumen), [
                'jalur' => [$snbp->id, $mandiri->id],
            ]);

        $this->assertSame(2, DokumenJalur::where('dokumen_id', $dokumen->id)->count());

        $this->actingAs($this->admin)
            ->delete(route('admin.dokumen.destroy', $dokumen));

        $this->assertDatabaseMissing('dokumen_master', ['kode' => 'IJAZAH_SMA']);
        $this->assertSame(0, DokumenJalur::where('dokumen_id', $dokumen->id)->count());
    }

    public function test_halaman_matrix_tarif_ukt_dan_inline_edit(): void
    {
        $periode = PeriodePmb::active()->firstOrFail();

        $this->actingAs($this->admin)
            ->get(route('admin.tarif-ukt.index', ['periode_id' => $periode->id]))
            ->assertOk()
            ->assertSee('Matrix Tarif UKT')
            ->assertSee('Teknik Grafika');
    }

    public function test_perubahan_tarif_langsung_terlihat_di_preview_ukt_pendaftar(): void
    {
        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $prodi = Prodi::where('kode', 'TGF')->firstOrFail();
        $golongan = GolonganUkt::where('kode', 'II')->firstOrFail();

        $nominalBaru = 1234567;

        $this->actingAs($this->admin)
            ->post(route('admin.tarif-ukt.update'), [
                'periode_id' => $periode->id,
                'tarif'      => [
                    $jalur->id.'-'.$prodi->id.'-'.$golongan->id => $nominalBaru,
                ],
            ])
            ->assertRedirect(route('admin.tarif-ukt.index', ['periode_id' => $periode->id]))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tarif_ukt', [
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_id'    => $prodi->id,
            'golongan_id' => $golongan->id,
            'nominal'     => $nominalBaru,
        ]);

        // Preview UKT pendorfatar memakai nominal terbaru.
        $pendaftar = User::where('email', 'pendaftar@pmb.ac.id')->firstOrFail();

        $this->actingAs($pendaftar)
            ->getJson(route('pendaftar.pilihan.preview', [
                'jalur_id'    => $jalur->id,
                'prodi_1_id'  => $prodi->id,
                'golongan_id' => $golongan->id,
            ]))
            ->assertOk()
            ->assertJson(['nominal' => $nominalBaru]);

        $this->assertSame(
            $nominalBaru,
            TarifUkt::where('periode_id', $periode->id)
                ->where('jalur_id', $jalur->id)
                ->where('prodi_id', $prodi->id)
                ->where('golongan_id', $golongan->id)
                ->value('nominal')
        );
    }

    public function test_validasi_form_menampilkan_pesan_error(): void
    {
        $this->actingAs($this->admin)
            ->from('/admin/fakultas/create')
            ->post('/admin/fakultas', [])
            ->assertRedirect('/admin/fakultas/create')
            ->assertSessionHasErrors(['kode', 'nama']);

        $this->actingAs($this->admin)
            ->from('/admin/jalur-masuk/create')
            ->post('/admin/jalur-masuk', [
                'kode'   => 'SNBP',
                'nama'   => 'Duplikat',
                'urutan' => 1,
            ])
            ->assertSessionHasErrors('kode');
    }
}
