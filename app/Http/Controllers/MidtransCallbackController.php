<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\MidtransSignature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransCallbackController extends Controller
{
    /**
     * Callback / notifikasi pembayaran dari Midtrans.
     */
    public function __invoke(Request $request, MidtransSignature $signature): JsonResponse
    {
        $notif = $request->json()->all();

        if (! is_array($notif) || $notif === []) {
            $notif = $request->all();
        }

        // Signature wajib valid sebelum data notifikasi diproses.
        if (! $signature->valid($notif, $request)) {
            return response()->json(['message' => 'Signature tidak valid.'], 403);
        }

        $orderId = (string) ($notif['order']['invoice_number'] ?? $notif['order_id'] ?? '');

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (! $pembayaran) {
            return response()->json(['message' => 'Order ID tidak ditemukan.'], 404);
        }

        $rawStatus = (string) ($notif['transaction']['status'] ?? $notif['transaction_status'] ?? '');
        $status = $this->mapStatus(strtolower($rawStatus));

        // Status lunas tidak boleh diturunkan kembali menjadi pending.
        if ($pembayaran->isPaid() && $status !== 'paid') {
            $status = 'paid';
        }

        $attributes = ['status' => $status];

        if ($status === 'paid' && ! $pembayaran->paid_at) {
            $attributes['paid_at'] = now();
        }

        $paymentChannel = $notif['channel']['id']
            ?? $notif['service']['id']
            ?? $notif['payment_type']
            ?? null;

        if (! empty($paymentChannel)) {
            $attributes['metode'] = $paymentChannel;
        }

        $pembayaran->update($attributes);

        return response()->json(['message' => 'OK']);
    }

    /**
     * Pemetaan status transaksi DOKU / gateway ke status tagihan internal.
     */
    private function mapStatus(string $transactionStatus): string
    {
        return match ($transactionStatus) {
            'success', 'settlement', 'capture'      => 'paid',
            'expired', 'expire'                     => 'expired',
            'failed', 'deny', 'cancel', 'failure'   => 'failed',
            default                                 => 'pending',
        };
    }
}
