<?php

namespace App\Services;

class MidtransSignature
{
    /**
     * Verifikasi signature_key notifikasi Midtrans.
     *
     * Rumus: sha512(order_id + status_code + gross_amount + server_key).
     *
     * @param  array<string, mixed>  $notification
     */
    public function valid(array $notification): bool
    {
        $serverKey = (string) config('midtrans.server_key');

        $orderId = (string) ($notification['order_id'] ?? '');
        $statusCode = (string) ($notification['status_code'] ?? '');
        $grossAmount = (string) ($notification['gross_amount'] ?? '');
        $signature = (string) ($notification['signature_key'] ?? '');

        if ($serverKey === '' || $orderId === '' || $statusCode === '' || $grossAmount === '' || $signature === '') {
            return false;
        }

        return hash_equals($this->signature($orderId, $statusCode, $grossAmount), $signature);
    }

    /**
     * Hitung signature_key untuk Order ID, status code, dan nominal tertentu.
     */
    public function signature(string $orderId, string $statusCode, string $grossAmount): string
    {
        return hash(
            'sha512',
            $orderId.$statusCode.$grossAmount.(string) config('midtrans.server_key')
        );
    }
}
