<?php

namespace Tests\Feature;

use App\Contracts\MidtransGateway;
use App\Models\GolonganUkt;
use App\Models\JalurMasuk;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\PeriodePmb;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Fakes\FakeMidtransGateway;
use Tests\TestCase;

class PembayaranMidtransTest extends TestCase
{
    use RefreshDatabase;

    private const SERVER_KEY = 'test-server-key';

    private FakeMidtransGateway $midtrans;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Storage::fake('local');

        config([
            'midtrans.server_key' => self::SERVER_KEY,
            'midtrans.client_key' => 'test-client-key',
            'midtrans.is_production' => false,
        ]);

        $this->midtrans = new FakeMidtransGateway();
        $this->app->instance(MidtransGateway::class, $this->midtrans);
    }

    /**
     * Buat pendaftar + pendaftaran terverifikasi beserta tagihannya.
     *
     * @param  array<string, string>  $statusTagihan
     * @return array{0: User, 1: Pendaftaran, 2: \Illuminate\Support\Collection<int, Pembayaran>}
     */
    private function buatTagihan(array $statusTagihan = ['pendaftaran' => 'pending', 'ukt' => 'pending']): array
    {
        $user = User::factory()->create(['role' => 'pendaftar']);

        \App\Models\PendaftarProfile::create([
            'user_id'         => $user->id,
            'nik'             => '317101'.mt_rand(1000000, 9999999),
            'nisn'            => (string) mt_rand(1000000000, 9999999999),
            'tempat_lahir'    => 'Jakarta',
            'tanggal_lahir'   => '2006-07-07',
            'jenis_kelamin'   => 'L',
            'alamat'          => 'Jl. Contoh No. 2',
            'provinsi'        => 'DKI Jakarta',
            'kota'            => 'Jakarta Selatan',
            'asal_sekolah'    => 'SMA Negeri 2 Jakarta',
            'tahun_lulus'     => 2024,
            'nama_ortu'       => 'Budi Santoso',
            'pekerjaan_ortu'  => 'Karyawan',
            'penghasilan_ortu' => 4_500_000,
            'golongan_id'     => GolonganUkt::where('kode', 'II')->firstOrFail()->id,
        ]);

        $periode = PeriodePmb::active()->firstOrFail();
        $jalur = JalurMasuk::where('kode', 'MANDIRI')->firstOrFail();
        $prodi = Prodi::where('kode', 'TGF')->firstOrFail();

        $pendaftaran = Pendaftaran::create([
            'user_id'     => $user->id,
            'periode_id'  => $periode->id,
            'jalur_id'    => $jalur->id,
            'prodi_1_id'  => $prodi->id,
            'golongan_id' => GolonganUkt::where('kode', 'II')->firstOrFail()->id,
            'status'      => 'terverifikasi',
            'nomor_pendaftaran' => 'PMB-'.now()->year.'-0010',
        ]);

        $tagihan = collect();

        foreach ($statusTagihan as $jenis => $status) {
            $nominal = $jenis === 'pendaftaran'
                ? 250_000
                : \App\Models\TarifUkt::where('periode_id', $periode->id)
                    ->where('jalur_id', $jalur->id)
                    ->where('prodi_id', $prodi->id)
                    ->where('golongan_id', $pendaftaran->golongan_id)
                    ->value('nominal');

            $tagihan->push(Pembayaran::create([
                'pendaftaran_id' => $pendaftaran->id,
                'jenis'          => $jenis,
                'nominal'        => (int) $nominal,
                'status'         => $status,
            ]));
        }

        return [$user, $pendaftaran, $tagihan];
    }

    private function signature(string $orderId, string $statusCode, string $grossAmount): string
    {
        return hash('sha512', $orderId.$statusCode.$grossAmount.self::SERVER_KEY);
    }

    public function test_halaman_pembayaran_menampilkan_tagihan_pendaftaran_sebelum_ukt(): void
    {
        [$user] = $this->buatTagihan();

        $this->actingAs($user)
            ->get('/pendaftar/pembayaran')
            ->assertOk()
            ->assertSeeInOrder(['Biaya Pendaftaran', 'Uang Kuliah Tunggal (UKT)'])
            ->assertSee('Rp 250.000')
            ->assertSee('Bayar via Midtrans')
            ->assertSee('Unggah Bukti');
    }

    public function test_halaman_pembayaran_mengalihkan_bila_belum_ada_pendaftaran(): void
    {
        $user = User::factory()->create(['role' => 'pendaftar']);

        $this->actingAs($user)
            ->get('/pendaftar/pembayaran')
            ->assertRedirect(route('pendaftar.dashboard'));
    }

    public function test_ukt_tidak_bisa_dibayar_sebelum_tagihan_pendaftaran_lunas(): void
    {
        [$user, , $tagihan] = $this->buatTagihan();

        $ukt = $tagihan->firstWhere('jenis', 'ukt');

        $this->actingAs($user)
            ->from('/pendaftar/pembayaran')
            ->post(route('pendaftar.pembayaran.snap', $ukt))
            ->assertRedirect('/pendaftar/pembayaran')
            ->assertSessionHas('error');

        $this->assertNull($ukt->fresh()->order_id);
    }

    public function test_ukt_boleh_dibayar_setelah_tagihan_pendaftaran_lunas(): void
    {
        [$user, , $tagihan] = $this->buatTagihan([
            'pendaftaran' => 'paid',
            'ukt'         => 'pending',
        ]);

        $ukt = $tagihan->firstWhere('jenis', 'ukt');

        $response = $this->actingAs($user)
            ->post(route('pendaftar.pembayaran.snap', $ukt));

        $response->assertRedirectContains('https://app.midtrans.com/snap/v2/transactions/');
        $this->assertNotNull($ukt->fresh()->order_id);
    }

    public function test_snap_berhasil_menyimpan_order_id_token_dan_url(): void
    {
        [$user, , $tagihan] = $this->buatTagihan();

        $pendaftaranTagihan = $tagihan->firstWhere('jenis', 'pendaftaran');

        $this->actingAs($user)
            ->post(route('pendaftar.pembayaran.snap', $pendaftaranTagihan))
            ->assertRedirectContains('https://app.midtrans.com/snap/v2/transactions/');

        $pendaftaranTagihan->refresh();

        $this->assertNotNull($pendaftaranTagihan->order_id);
        $this->assertNotNull($pendaftaranTagihan->snap_token);
        $this->assertStringContainsString('app.midtrans.com', (string) $pendaftaranTagihan->snap_url);
        $this->assertSame('pending', $pendaftaranTagihan->status);

        // Parameter Snap: item details sesuai jenis tagihan dan nominal.
        $params = $this->midtrans->params;

        $this->assertSame($pendaftaranTagihan->nominal, $params['transaction_details']['gross_amount']);
        $this->assertSame('pendaftaran', $params['item_details'][0]['id']);
        $this->assertSame('Biaya Pendaftaran', $params['item_details'][0]['name']);
        $this->assertSame($pendaftaranTagihan->order_id, $params['transaction_details']['order_id']);
    }

    public function test_snap_gagal_ditampilkan_pesan_error(): void
    {
        [$user, , $tagihan] = $this->buatTagihan();

        $this->midtrans->shouldFail = true;

        $pendaftaranTagihan = $tagihan->firstWhere('jenis', 'pendaftaran');

        $this->actingAs($user)
            ->from('/pendaftar/pembayaran')
            ->post(route('pendaftar.pembayaran.snap', $pendaftaranTagihan))
            ->assertRedirect('/pendaftar/pembayaran')
            ->assertSessionHas('error');

        $this->assertNull($pendaftaranTagihan->fresh()->order_id);
    }

    public function test_callback_dengan_signature_tidak_valid_ditolak_403(): void
    {
        [$user, , $tagihan] = $this->buatTagihan();

        $pendaftaranTagihan = $tagihan->firstWhere('jenis', 'pendaftaran');

        $this->actingAs($user)
            ->post(route('pendaftar.pembayaran.snap', $pendaftaranTagihan));

        $orderId = $pendaftaranTagihan->fresh()->order_id;

        $this->postJson('/midtrans/callback', [
            'order_id'           => $orderId,
            'transaction_status' => 'settlement',
            'status_code'        => '200',
            'gross_amount'       => (string) $pendaftaranTagihan->nominal,
            'signature_key'      => 'signature-palsu',
        ])->assertStatus(403);

        $this->assertSame('pending', $pendaftaranTagihan->fresh()->status);
    }

    public function test_callback_settlement_menandai_tagihan_paid(): void
    {
        [$user, , $tagihan] = $this->buatTagihan();

        $pendaftaranTagihan = $tagihan->firstWhere('jenis', 'pendaftaran');

        $this->actingAs($user)
            ->post(route('pendaftar.pembayaran.snap', $pendaftaranTagihan));

        $orderId = $pendaftaranTagihan->fresh()->order_id;
        $grossAmount = (string) $pendaftaranTagihan->nominal;

        $this->postJson('/midtrans/callback', [
            'order_id'           => $orderId,
            'transaction_status' => 'settlement',
            'status_code'        => '200',
            'gross_amount'       => $grossAmount,
            'payment_type'       => 'bank_transfer',
            'signature_key'      => $this->signature($orderId, '200', $grossAmount),
        ])->assertStatus(200);

        $pendaftaranTagihan->refresh();

        $this->assertSame('paid', $pendaftaranTagihan->status);
        $this->assertNotNull($pendaftaranTagihan->paid_at);
        $this->assertSame('bank_transfer', $pendaftaranTagihan->metode);
    }

    public function test_callback_expire_dan_deny_mengubah_status(): void
    {
        [$user, , $tagihan] = $this->buatTagihan();

        $ukt = $tagihan->firstWhere('jenis', 'ukt');
        $orderId = 'PMB-TEST-EXPIRE';
        $grossAmount = (string) $ukt->nominal;

        $ukt->update(['order_id' => $orderId]);

        $this->postJson('/midtrans/callback', [
            'order_id'           => $orderId,
            'transaction_status' => 'expire',
            'status_code'        => '200',
            'gross_amount'       => $grossAmount,
            'signature_key'      => $this->signature($orderId, '200', $grossAmount),
        ])->assertStatus(200);

        $this->assertSame('expired', $ukt->fresh()->status);

        $orderIdGagal = 'PMB-TEST-DENY';
        $ukt->update(['order_id' => $orderIdGagal]);

        $this->postJson('/midtrans/callback', [
            'order_id'           => $orderIdGagal,
            'transaction_status' => 'deny',
            'status_code'        => '200',
            'gross_amount'       => $grossAmount,
            'signature_key'      => $this->signature($orderIdGagal, '200', $grossAmount),
        ])->assertStatus(200);

        $this->assertSame('failed', $ukt->fresh()->status);
    }

    public function test_callback_dengan_order_id_tidak_diketahui_menghasilkan_404(): void
    {
        $orderId = 'PMB-TIDAK-ADA';
        $grossAmount = '250000';

        $this->postJson('/midtrans/callback', [
            'order_id'           => $orderId,
            'transaction_status' => 'settlement',
            'status_code'        => '200',
            'gross_amount'       => $grossAmount,
            'signature_key'      => $this->signature($orderId, '200', $grossAmount),
        ])->assertStatus(404);
    }

    public function test_upload_bukti_manual_menunggu_review_panitia(): void
    {
        [$user, , $tagihan] = $this->buatTagihan();

        $ukt = $tagihan->firstWhere('jenis', 'ukt');

        $this->actingAs($user)
            ->from('/pendaftar/pembayaran')
            ->post(route('pendaftar.pembayaran.bukti', $ukt), [
                'bukti' => UploadedFile::fake()->create('bukti.pdf', 200, 'application/pdf'),
            ])
            ->assertRedirect('/pendaftar/pembayaran')
            ->assertSessionHas('success');

        $ukt->refresh();

        $this->assertSame('pending', $ukt->status);
        $this->assertNotNull($ukt->bukti_path);
        Storage::disk('local')->assertExists($ukt->bukti_path);
    }

    public function test_endpoint_polling_mengembalikan_status_tagihan(): void
    {
        [$user, , $tagihan] = $this->buatTagihan();

        $response = $this->actingAs($user)->getJson('/pendaftar/pembayaran/status');

        $response->assertOk()
            ->assertJsonCount(2, 'tagihan')
            ->assertJsonPath('tagihan.0.jenis', 'pendaftaran')
            ->assertJsonPath('tagihan.0.status', 'pending')
            ->assertJsonPath('tagihan.1.jenis', 'ukt');
    }

    public function test_tagihan_lunas_tidak_dapat_dibayar_lagi(): void
    {
        [$user, , $tagihan] = $this->buatTagihan([
            'pendaftaran' => 'paid',
            'ukt'         => 'paid',
        ]);

        $ukt = $tagihan->firstWhere('jenis', 'ukt');

        $this->actingAs($user)
            ->from('/pendaftar/pembayaran')
            ->post(route('pendaftar.pembayaran.snap', $ukt))
            ->assertRedirect('/pendaftar/pembayaran')
            ->assertSessionHas('error');

        $this->assertNull($ukt->fresh()->order_id);
    }
}
