<?php

namespace Tests\Feature;

use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class Fase10E2ETest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Notification::fake();

        config(['midtrans.server_key' => 'test-server-key-123']);
    }

    public function test_end_to_end_registrasi_sampai_kartu(): void
    {
        // 1. Registrasi pendaftar baru
        $user = User::factory()->create(['role' => 'pendaftar']);

        $this->actingAs($user)
            ->post('/pendaftar/profil/1', [
                'nik'           => '3171012305980010',
                'nisn'          => '0123456710',
                'tempat_lahir'  => 'Jakarta',
                'tanggal_lahir' => '2006-08-08',
                'jenis_kelamin' => 'L',
            ])
            ->assertRedirect(route('pendaftar.profil.step', 2));

        $this->actingAs($user)
            ->post('/pendaftar/profil/2', [
                'alamat'   => 'Jl. E2E No. 10',
                'provinsi' => 'DKI Jakarta',
                'kota'     => 'Jakarta Pusat',
            ])
            ->assertRedirect(route('pendaftar.profil.step', 3));

        $this->actingAs($user)
            ->post('/pendaftar/profil/3', [
                'asal_sekolah' => 'SMA E2E',
                'tahun_lulus'  => 2024,
            ])
            ->assertRedirect(route('pendaftar.profil.step', 4));

        $this->actingAs($user)
            ->post('/pendaftar/profil/4', [
                'nama_ortu'        => 'Orang Tua E2E',
                'pekerjaan_ortu'   => 'PNS',
                'penghasilan_ortu' => 5_000_000,
                'golongan_id'      => GolonganUkt::where('kode', 'II')->firstOrFail()->id,
            ])
            ->assertRedirect(route('pendaftar.pilihan'));

        // 2. Pilihan jalur & prodi
        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();
        $prodi = Prodi::where('kode', 'TGF')->firstOrFail();

        $this->actingAs($user)
            ->post('/pendaftar/pilihan', [
                'jalur_id'    => $jalur->id,
                'prodi_1_id'  => $prodi->id,
                'golongan_id' => GolonganUkt::where('kode', 'II')->firstOrFail()->id,
            ])
            ->assertRedirect(route('pendaftar.pilihan'));

        $pendaftaran = Pendaftaran::where('user_id', $user->id)
            ->where('periode_id', $periode->id)
            ->firstOrFail();
        $this->assertSame('draft', $pendaftaran->status);

        // 3. Dokumen - upload dokumen wajib untuk MANDIRI
        $dokumenIds = \App\Models\DokumenJalur::where('jalur_id', $jalur->id)
            ->pluck('dokumen_id');

        foreach ($dokumenIds as $dokumenId) {
            $this->actingAs($user)
                ->from(route('pendaftar.dokumen'))
                ->post(route('pendaftar.dokumen.upload', $dokumenId), [
                    'file' => \Illuminate\Http\UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
                ])
                ->assertRedirect(route('pendaftar.dokumen'));
        }

        // 4. Ajukan verifikasi
        $this->actingAs($user)
            ->post(route('pendaftar.dokumen.verifikasi'))
            ->assertRedirect(route('pendaftar.dokumen'))
            ->assertSessionHas('success');

        $pendaftaran->refresh();
        $this->assertSame('menunggu_verifikasi', $pendaftaran->status);

        // 5. Admin verifikasi pendaftaran + dokumen
        $admin = User::where('email', 'admin@pmb.ac.id')->firstOrFail();

        // Tandai semua dokumen valid
        foreach ($pendaftaran->dokumen as $dok) {
            $this->actingAs($admin)
                ->post(route('admin.dokumen.status', $dok), [
                    'status'  => 'valid',
                    'catatan' => 'Dokumen lengkap.',
                ])
                ->assertSessionHas('success');
        }

        // Verifikasi pendaftaran
        $this->actingAs($admin)
            ->post(route('admin.pendaftaran.verifikasi', $pendaftaran))
            ->assertRedirect()
            ->assertSessionHas('success');

        $pendaftaran->refresh();
        $this->assertSame('terverifikasi', $pendaftaran->status);

        // Tagihan dibuat otomatis (2 tagihan untuk Mandiri: pendaftaran + UKT)
        $this->assertSame(2, $pendaftaran->pembayaran()->count());

        // 6. Pembayaran - bayar tagihan pendaftaran lalu UKT
        $tagihanPendaftaran = $pendaftaran->pembayaran()->where('jenis', 'pendaftaran')->firstOrFail();
        $tagihanUKT = $pendaftaran->pembayaran()->where('jenis', 'ukt')->firstOrFail();

        // Simulasi callback Midtrans untuk tagihan pendaftaran
        $orderId = $tagihanPendaftaran->order_id ?? 'PMB-TEST-E2E';
        $tagihanPendaftaran->update(['order_id' => $orderId]);

        $serverKey = config('midtrans.server_key') ?? 'test-key';
        $signature = hash('sha512', $orderId.'200'.$tagihanPendaftaran->nominal.$serverKey);

        $this->postJson('/midtrans/callback', [
            'order_id'           => $orderId,
            'transaction_status' => 'settlement',
            'status_code'        => '200',
            'gross_amount'       => (string) $tagihanPendaftaran->nominal,
            'payment_type'       => 'bank_transfer',
            'signature_key'      => $signature,
        ])->assertStatus(200);

        $tagihanPendaftaran->refresh();
        $this->assertSame('paid', $tagihanPendaftaran->status);

        // Bayar UKT
        $tagihanUKT->update(['order_id' => 'PMB-TEST-E2E-UKT']);
        $signatureUKT = hash('sha512', 'PMB-TEST-E2E-UKT200'.$tagihanUKT->nominal.$serverKey);

        $this->postJson('/midtrans/callback', [
            'order_id'           => 'PMB-TEST-E2E-UKT',
            'transaction_status' => 'settlement',
            'status_code'        => '200',
            'gross_amount'       => (string) $tagihanUKT->nominal,
            'payment_type'       => 'qris',
            'signature_key'      => $signatureUKT,
        ])->assertStatus(200);

        $tagihanUKT->refresh();
        $this->assertSame('paid', $tagihanUKT->status);

        // 7. Admin pengumuman - set hasil lulus
        $this->actingAs($admin)
            ->post(route('admin.pengumuman.set-hasil', $pendaftaran), [
                'hasil_kelulusan' => 'lulus',
                'catatan_admin'   => 'Selamat, Anda diterima.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $pendaftaran->refresh();
        $this->assertSame('lulus', $pendaftaran->hasil_kelulusan);

        // Notifikasi dikirim ke pendaftar
        Notification::assertSentTo($user, \App\Notifications\PengumumanDikeluarkan::class);

        // 8. Pendaftar lihat pengumuman
        $this->actingAs($user)
            ->get('/pendaftar/pengumuman')
            ->assertOk()
            ->assertSee('Selamat, Anda Lulus!')
            ->assertSee($pendaftaran->nomor_pendaftaran);

        // 9. Pendaftar lihat kartu peserta (semua tagihan lunas + lulus)
        $this->actingAs($user)
            ->get('/pendaftar/kartu')
            ->assertOk()
            ->assertSee('Kartu Peserta PMB')
            ->assertSee($pendaftaran->nomor_pendaftaran)
            ->assertSee($prodi->nama);
    }

    public function test_pendaftar_a_tidak_bisa_akses_data_pendaftar_b(): void
    {
        $userA = User::factory()->create(['role' => 'pendaftar']);
        $userB = User::factory()->create(['role' => 'pendaftar']);

        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::firstOrFail();

        $pendaftaranB = Pendaftaran::create([
            'user_id'     => $userB->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => 'terverifikasi',
            'nomor_pendaftaran' => 'PMB-TEST-POLICY-001',
            'hasil_kelulusan' => 'lulus',
        ]);

        // Pendaftar A mencoba mengakses detail pendaftaran B via admin route
        $this->actingAs($userA)
            ->get(route('admin.pendaftaran.show', $pendaftaranB))
            ->assertForbidden();

        // Pendaftar A mencoba mengakses pembayaran B
        $pembayaranB = Pembayaran::create([
            'pendaftaran_id' => $pendaftaranB->id,
            'jenis'          => 'ukt',
            'nominal'        => 4000000,
            'status'         => 'pending',
        ]);

        $this->actingAs($userA)
            ->post(route('admin.pembayaran.verifikasi', $pembayaranB), [
                'status'      => 'paid',
                'catatan_admin' => 'Test.',
            ])
            ->assertForbidden();
    }

    public function test_admin_pengumuman_bulk_set_hasil(): void
    {
        $admin = User::where('email', 'admin@pmb.ac.id')->firstOrFail();
        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::firstOrFail();

        $pendaftaran1 = Pendaftaran::create([
            'user_id'     => User::factory()->create(['role' => 'pendaftar'])->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => 'terverifikasi',
            'nomor_pendaftaran' => 'PMB-TEST-BULK-001',
            'hasil_kelulusan' => 'belum',
        ]);

        $pendaftaran2 = Pendaftaran::create([
            'user_id'     => User::factory()->create(['role' => 'pendaftar'])->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => 'terverifikasi',
            'nomor_pendaftaran' => 'PMB-TEST-BULK-002',
            'hasil_kelulusan' => 'belum',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.pengumuman.bulk'), [
                'pilihan'         => [$pendaftaran1->id, $pendaftaran2->id],
                'hasil_kelulusan' => 'lulus',
                'catatan_admin'   => 'Lulus bulk.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('lulus', $pendaftaran1->fresh()->hasil_kelulusan);
        $this->assertSame('lulus', $pendaftaran2->fresh()->hasil_kelulusan);
        $this->assertSame('Lulus bulk.', $pendaftaran1->fresh()->catatan_admin);
    }

    public function test_admin_verifikasi_pembayaran_manual(): void
    {
        $admin = User::where('email', 'admin@pmb.ac.id')->firstOrFail();
        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::firstOrFail();

        $user = User::factory()->create(['role' => 'pendaftar']);

        $pendaftaran = Pendaftaran::create([
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => 'terverifikasi',
            'nomor_pendaftaran' => 'PMB-TEST-PAY-001',
        ]);

        $pembayaran = Pembayaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis'          => 'ukt',
            'nominal'        => 4000000,
            'status'         => 'pending',
            'bukti_path'     => 'bukti/test.pdf',
            'metode'         => 'transfer manual',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pembayaran.verifikasi.index'))
            ->assertOk()
            ->assertSee($user->name)
            ->assertSee('Rp 4.000.000');

        $this->actingAs($admin)
            ->post(route('admin.pembayaran.verifikasi', $pembayaran), [
                'status'      => 'paid',
                'catatan_admin' => 'Transfer dikonfirmasi.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('paid', $pembayaran->fresh()->status);
        $this->assertNotNull($pembayaran->fresh()->paid_at);
        $this->assertSame('Transfer dikonfirmasi.', $pembayaran->fresh()->catatan_admin);
    }

    public function test_kartu_hanya_tersedia_jika_lulus_dan_lunas(): void
    {
        $user = User::factory()->create(['role' => 'pendaftar']);
        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::where('kode', 'SNBP')->firstOrFail();
        $prodi = Prodi::firstOrFail();
        $golongan = GolonganUkt::firstOrFail();

        // Pendaftar lulus tetapi belum ada pembayaran (SNBP gratis, tapi kita buat tagihan manual untuk uji)
        $pendaftaran = Pendaftaran::create([
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => $golongan->id,
            'status'      => 'terverifikasi',
            'hasil_kelulusan' => 'lulus',
            'nomor_pendaftaran' => 'PMB-TEST-KARTU-001',
        ]);

        // SNBP gratis, tidak ada tagihan, sehingga tidak bisa lihat kartu (semua tagihan harus lunas, tapi tidak ada tagihan)
        $this->actingAs($user)
            ->get('/pendaftar/kartu')
            ->assertRedirect(route('pendaftar.pengumuman'))
            ->assertSessionHas('info');

        // Buat tagihan dan bayar
        Pembayaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis'          => 'ukt',
            'nominal'        => 4000000,
            'status'         => 'paid',
            'paid_at'        => now(),
        ]);

        $this->actingAs($user)
            ->get('/pendaftar/kartu')
            ->assertOk()
            ->assertSee('Kartu Peserta PMB');
    }
}
