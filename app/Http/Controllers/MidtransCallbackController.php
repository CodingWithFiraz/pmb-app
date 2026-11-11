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

        // Signature_key wajib valid sebelum data notifikasi diproses.
        if (! $signature->valid($notif)) {
            return response()->json(['message' => 'Signature tidak valid.'], 403);
        }

        $orderId = (string) ($notif['order_id'] ?? '');

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (! $pembayaran) {
            return response()->json(['message' => 'Order ID tidak ditemukan.'], 404);
        }

        $status = $this->mapStatus((string) ($notif['transaction_status'] ?? ''));

        // Status lunas tidak boleh diturunkan kembali menjadi pending.
        if ($pembayaran->isPaid() && $status !== 'paid') {
            $status = 'paid';
        }

        $attributes = ['status' => $status];

        if ($status === 'paid' && ! $pembayaran->paid_at) {
            $attributes['paid_at'] = now();
        }

        if (! empty($notif['payment_type'])) {
            $attributes['metode'] = $notif['payment_type'];
        }

        $pembayaran->update($attributes);

        return response()->json(['message' => 'OK']);
    }

    /**
     * Pemetaan transaction_status Midtrans ke status tagihan internal.
     */
    private function mapStatus(string $transactionStatus): string
    {
        return match ($transactionStatus) {
            'settlement', 'capture' => 'paid',
            'expire'                 => 'expired',
            'deny', 'cancel', 'failure' => 'failed',
            default                  => 'pending',
        };
    }
}
