<?php

namespace App\Http\Controllers\Applicant;

use App\Contracts\MidtransGateway;
use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\PeriodePmb;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class PembayaranController extends Controller
{
    /**
     * Halaman daftar tagihan pendaftar.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $pendaftaran = $this->pendaftaran($request);

        if (! $pendaftaran) {
            return redirect()
                ->route('pendaftar.dashboard')
                ->with('info', 'Belum ada tagihan. Selesaikan proses verifikasi pendaftaran terlebih dahulu.');
        }

        $tagihan = $this->urutkanTagihan($pendaftaran);

        return view('pendaftar.pembayaran', [
            'pendaftaran'        => $pendaftaran,
            'tagihan'            => $tagihan,
            'tagihanPendaftaran' => $tagihan->firstWhere('jenis', 'pendaftaran'),
            'total'              => $tagihan->sum('nominal'),
            'totalLunas'         => $tagihan->where('status', 'paid')->sum('nominal'),
        ]);
    }

    /**
     * Buat transaksi Snap lalu arahkan pendaftar ke halaman Midtrans.
     */
    public function snap(Request $request, Pembayaran $pembayaran, MidtransGateway $midtrans): RedirectResponse
    {
        $pendaftaran = $this->pendaftaran($request);

        if (! $pendaftaran || $pembayaran->pendaftaran_id !== $pendaftaran->id) {
            abort(403, 'Tagihan tidak ditemukan.');
        }

        if ($pembayaran->isPaid()) {
            return back()->with('error', 'Tagihan ini sudah lunas.');
        }

        // Tagihan UKT baru bisa dibayar setelah tagihan pendaftaran lunas.
        if ($pembayaran->jenis === 'ukt') {
            $tagihanPendaftaran = $pendaftaran->pembayaran()->where('jenis', 'pendaftaran')->first();

            if ($tagihanPendaftaran && ! $this->tagihanLunas($tagihanPendaftaran)) {
                return back()->with('error', 'Selesaikan pembayaran biaya pendaftaran terlebih dahulu.');
            }
        }

        if (! config('midtrans.server_key')) {
            return back()->with('error', 'Konfigurasi Midtrans belum lengkap (MIDTRANS_SERVER_KEY).');
        }

        /** @var User $user */
        $user = $request->user();

        $orderId = $this->orderId($pembayaran);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $pembayaran->nominal,
            ],
            'item_details' => [
                [
                    'id'       => $pembayaran->jenis,
                    'price'    => $pembayaran->nominal,
                    'quantity' => 1,
                    'name'     => $pembayaran->jenisLabel(),
                ],
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone ?? '',
            ],
            'enabled_payments' => ['bank_transfer', 'qris', 'gopay'],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s'),
                'unit'       => 'hour',
                'duration'   => 24,
            ],
        ];

        try {
            $result = $midtrans->createSnapTransaction($params);
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Gagal membuat transaksi pembayaran: '.$e->getMessage());
        }

        if (($result['snap_url'] ?? '') === '' && ($result['snap_token'] ?? '') === '') {
            return back()->with('error', 'Midtrans tidak mengembalikan Snap token.');
        }

        $pembayaran->update([
            'order_id'      => $result['order_id'],
            'snap_token'    => $result['snap_token'],
            'snap_url'      => $result['snap_url'],
            'status'        => 'pending',
            'metode'        => 'midtrans',
            'catatan_admin' => null,
        ]);

        return redirect()->away($result['snap_url']);
    }

    /**
     * Fallback manual: unggah bukti bayar untuk diverifikasi panitia.
     */
    public function bukti(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $pendaftaran = $this->pendaftaran($request);

        if (! $pendaftaran || $pembayaran->pendaftaran_id !== $pendaftaran->id) {
            abort(403, 'Tagihan tidak ditemukan.');
        }

        if ($pembayaran->isPaid()) {
            return back()->with('error', 'Tagihan ini sudah lunas.');
        }

        $request->validate(
            [
                'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            ],
            [
                'bukti.required' => 'Berkah bukti pembayaran belum dipilih.',
                'bukti.mimes'    => 'Format berkas harus JPG, PNG, atau PDF.',
                'bukti.max'      => 'Ukuran berkas maksimal 2048 KB.',
            ],
            [
                'bukti' => 'berkas bukti pembayaran',
            ]
        );

        $file = $request->file('bukti');

        $path = $file->storeAs(
            'bukti/'.$pendaftaran->id,
            'bukti-'.$pembayaran->id.'-'.now()->format('YmdHis').'.'.$file->extension(),
            'local'
        );

        if ($pembayaran->bukti_path && Storage::disk('local')->exists($pembayaran->bukti_path)) {
            Storage::disk('local')->delete($pembayaran->bukti_path);
        }

        // Menunggu review panitia (di-review pada tahap admin pembayaran).
        $pembayaran->update([
            'bukti_path'     => $path,
            'status'         => 'pending',
            'metode'         => 'transfer manual',
            'catatan_admin'  => null,
        ]);

        return back()->with('success', 'Bukti pembayaran diunggah dan menunggu peninjauan panitia.');
    }

    /**
     * Endpoint polling status tagihan (realtime).
     */
    public function status(Request $request): JsonResponse
    {
        $pendaftaran = $this->pendaftaran($request);

        if (! $pendaftaran) {
            return response()->json(['tagihan' => []]);
        }

        $tagihan = $this->urutkanTagihan($pendaftaran)
            ->map(fn (Pembayaran $p): array => [
                'id'           => $p->id,
                'jenis'        => $p->jenis,
                'jenis_label'  => $p->jenisLabel(),
                'nominal'      => $p->nominal,
                'status'       => $p->status,
                'status_label' => $p->statusLabel(),
                'order_id'     => $p->order_id,
                'paid_at'      => $p->paid_at?->toIso8601String(),
            ])
            ->values();

        return response()->json(['tagihan' => $tagihan]);
    }

    private function pendaftaran(Request $request): ?Pendaftaran
    {
        /** @var User $user */
        $user = $request->user();

        $periode = PeriodePmb::getActive();

        if (! $periode) {
            return null;
        }

        return $user->pendaftaran()
            ->with(['periode', 'jalur', 'prodi', 'prodiAlternatif', 'golongan'])
            ->where('periode_id', $periode->id)
            ->first();
    }

    /**
     * Tagihan biaya pendaftaran ditampilkan lebih dulu, lalu UKT.
     *
     * @return \Illuminate\Support\Collection<int, Pembayaran>
     */
    private function urutkanTagihan(Pendaftaran $pendaftaran)
    {
        return $pendaftaran->pembayaran()
            ->get()
            ->sortBy(fn (Pembayaran $p): int => $p->jenis === 'pendaftaran' ? 0 : 1)
            ->values();
    }

    private function tagihanLunas(Pembayaran $pembayaran): bool
    {
        return $pembayaran->status === 'paid';
    }

    /**
     * Order ID unik untuk transaksi Midtrans.
     */
    private function orderId(Pembayaran $pembayaran): string
    {
        return 'PMB-'.str_pad((string) $pembayaran->id, 6, '0', STR_PAD_LEFT)
            .'-'.strtoupper($pembayaran->jenis)
            .'-'.now()->format('ymdHis')
            .'-'.strtoupper(substr(md5((string) random_int(1, 999999)), 0, 4));
    }
}
