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
    public function index(Request $request, MidtransGateway $midtrans): View|RedirectResponse
    {
        $pendaftaran = $this->pendaftaran($request);

        if (! $pendaftaran) {
            return redirect()
                ->route('pendaftar.dashboard')
                ->with('info', 'Belum ada tagihan. Selesaikan proses verifikasi pendaftaran terlebih dahulu.');
        }

        $this->sinkronkanStatusDoku($pendaftaran, $midtrans);

        $tagihan = $this->urutkanTagihan($pendaftaran);

        return view('pendaftar.pembayaran', [
            'pendaftaran'          => $pendaftaran,
            'tagihan'              => $tagihan,
            'tagihanPendaftaran'   => $tagihan->firstWhere('jenis', 'pendaftaran'),
            'total'                => $tagihan->sum('nominal'),
            'totalLunas'           => $tagihan->where('status', 'paid')->sum('nominal'),
            'dokuClientId'         => config('doku.client_id') ?: config('midtrans.client_key', ''),
            'dokuIsProduction'     => (bool) (config('doku.is_production') ?? config('midtrans.is_production', false)),
        ]);
    }


    public function snap(Request $request, Pembayaran $pembayaran, MidtransGateway $midtrans): RedirectResponse|JsonResponse
    {
        $pendaftaran = $this->pendaftaran($request);

        if (! $pendaftaran || $pembayaran->pendaftaran_id !== $pendaftaran->id) {
            abort(403, 'Tagihan tidak ditemukan.');
        }

        if ($pembayaran->isPaid()) {
            $msg = 'Tagihan ini sudah lunas.';
            return ($request->expectsJson() || $request->ajax())
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        // Tagihan UKT baru bisa dibayar setelah tagihan pendaftaran lunas.
        if ($pembayaran->jenis === 'ukt') {
            $tagihanPendaftaran = $pendaftaran->pembayaran()->where('jenis', 'pendaftaran')->first();

            if ($tagihanPendaftaran && ! $this->tagihanLunas($tagihanPendaftaran)) {
                $msg = 'Selesaikan pembayaran biaya pendaftaran terlebih dahulu.';
                return ($request->expectsJson() || $request->ajax())
                    ? response()->json(['message' => $msg], 422)
                    : back()->with('error', $msg);
            }
        }

        $secretKey = config('doku.secret_key') ?: config('midtrans.server_key');
        if (! $secretKey) {
            $msg = 'Konfigurasi DOKU belum lengkap (DOKU_SECRET_KEY).';
            return ($request->expectsJson() || $request->ajax())
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        /** @var User $user */
        $user = $request->user();

        $orderId = $this->orderId($pembayaran);

        // Pemetaan pilihan metode pembayaran DOKU yang aktif (Virtual Account & Alfamart/Indomaret)
        $selectedMethod = (string) $request->input('payment_method', 'virtual_account');
        $dokuMethodMap = [
            'virtual_account'   => [
                'VIRTUAL_ACCOUNT_BCA',
                'VIRTUAL_ACCOUNT_BANK_MANDIRI',
                'VIRTUAL_ACCOUNT_BNI',
                'VIRTUAL_ACCOUNT_BRI',
                'VIRTUAL_ACCOUNT_BANK_SYARIAH_INDONESIA',
                'VIRTUAL_ACCOUNT_PERMATA',
                'VIRTUAL_ACCOUNT_CIMB',
                'VIRTUAL_ACCOUNT_DANAMON',
                'VIRTUAL_ACCOUNT_DOKU',
            ],
            'convenience_store' => [
                'ONLINE_TO_OFFLINE_ALFA',
                'ONLINE_TO_OFFLINE_INDOMARET',
            ],
        ];

        $dokuPaymentMethods = $dokuMethodMap[$selectedMethod] ?? config('doku.payment_method_types');

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
            'enabled_payments'          => ['virtual_account', 'convenience_store'],
            'doku_payment_method_types' => $dokuPaymentMethods,
        ];

        try {
            $result = $midtrans->createSnapTransaction($params);
        } catch (Throwable $e) {
            report($e);

            $msg = 'Gagal membuat transaksi pembayaran: '.$e->getMessage();
            return ($request->expectsJson() || $request->ajax())
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        if (($result['snap_url'] ?? '') === '' && ($result['snap_token'] ?? '') === '') {
            $msg = 'DOKU tidak mengembalikan URL pembayaran.';
            return ($request->expectsJson() || $request->ajax())
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $metodeLabel = match ($selectedMethod) {
            'virtual_account'   => 'doku - virtual account',
            'convenience_store' => 'doku - alfamart / indomaret',
            default             => 'doku',
        };

        $pembayaran->update([
            'order_id'      => $result['order_id'],
            'snap_token'    => $result['snap_token'],
            'snap_url'      => $result['snap_url'],
            'status'        => 'pending',
            'metode'        => $metodeLabel,
            'catatan_admin' => null,
        ]);

        // Jika request dari JS (AJAX/fetch), kembalikan URL checkout DOKU sebagai JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'payment_url'   => $result['snap_url'],
                'snap_token'    => $result['snap_token'],
                'client_id'     => config('doku.client_id') ?: config('midtrans.client_key'),
                'is_production' => (bool) (config('doku.is_production') ?? config('midtrans.is_production', false)),
            ]);
        }

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
    public function status(Request $request, MidtransGateway $midtrans): JsonResponse
    {
        $pendaftaran = $this->pendaftaran($request);

        if (! $pendaftaran) {
            return response()->json(['tagihan' => []]);
        }

        $this->sinkronkanStatusDoku($pendaftaran, $midtrans);

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
     * Cek aktif status transaksi ke API DOKU untuk setiap tagihan pending yang sudah memiliki order_id.
     * Dengan ini status pembayaran otomatis berubah menjadi Lunas begitu keluar dari pop-up,
     * bahkan saat dijalankan di localhost (127.0.0.1).
     */
    private function sinkronkanStatusDoku(Pendaftaran $pendaftaran, MidtransGateway $midtrans): void
    {
        if (! method_exists($midtrans, 'checkTransactionStatus')) {
            return;
        }

        $pendingList = $pendaftaran->pembayaran()
            ->where('status', 'pending')
            ->whereNotNull('order_id')
            ->get();

        foreach ($pendingList as $pembayaran) {
            $check = $midtrans->checkTransactionStatus((string) $pembayaran->order_id);

            if (! $check || ($check['status'] ?? 'pending') === 'pending') {
                continue;
            }

            $attributes = ['status' => $check['status']];

            if ($check['status'] === 'paid' && ! $pembayaran->paid_at) {
                $attributes['paid_at'] = now();
            }

            if (! empty($check['channel'])) {
                $attributes['metode'] = $check['channel'];
            }

            $pembayaran->update($attributes);
        }
    }

  
    private function orderId(Pembayaran $pembayaran): string
    {
        return 'PMB-'.str_pad((string) $pembayaran->id, 6, '0', STR_PAD_LEFT)
            .'-'.strtoupper($pembayaran->jenis)
            .'-'.now()->format('ymdHis')
            .'-'.strtoupper(substr(md5((string) random_int(1, 999999)), 0, 4));
    }
}
