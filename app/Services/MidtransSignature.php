<?php

namespace App\Services;

use Illuminate\Http\Request;

class MidtransSignature
{
    /**
     * Verifikasi signature notifikasi pembayaran (mendukung DOKU HMAC-SHA256 & fallback SHA512).
     *
     * @param  array<string, mixed>  $notification
     */
    public function valid(array $notification, ?Request $request = null): bool
    {
        $secretKey = (string) (config('doku.secret_key') ?: config('midtrans.server_key'));

        if ($secretKey === '') {
            return false;
        }

        // 1. Verifikasi Header Signature DOKU (HMAC-SHA256) bila tersedia
        if ($request && $request->hasHeader('Signature')) {
            $clientId = (string) $request->header('Client-Id', (string) config('doku.client_id'));
            $requestId = (string) $request->header('Request-Id', '');
            $timestamp = (string) $request->header('Request-Timestamp', '');
            $targetPath = '/'.ltrim($request->path(), '/');
            $rawBody = (string) $request->getContent();
            $digest = base64_encode(hash('sha256', $rawBody, true));

            $component = "Client-Id:{$clientId}\n"
                ."Request-Id:{$requestId}\n"
                ."Request-Timestamp:{$timestamp}\n"
                ."Request-Target:{$targetPath}\n"
                ."Digest:{$digest}";

            $expectedDokuSig = 'HMACSHA256='.base64_encode(hash_hmac('sha256', $component, $secretKey, true));

            if (hash_equals($expectedDokuSig, (string) $request->header('Signature'))) {
                return true;
            }
        }

        // 2. Verifikasi payload signature_key
        $orderId = (string) ($notification['order_id'] ?? $notification['order']['invoice_number'] ?? '');
        $statusCode = (string) ($notification['status_code'] ?? '200');
        $grossAmount = (string) ($notification['gross_amount'] ?? $notification['order']['amount'] ?? '');
        $signature = (string) ($notification['signature_key'] ?? '');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signature === '') {
            return false;
        }

        return hash_equals($this->signature($orderId, $statusCode, $grossAmount), $signature);
    }

    /**
     * Hitung signature_key untuk Order ID, status code, dan nominal tertentu.
     */
    public function signature(string $orderId, string $statusCode, string $grossAmount): string
    {
        $secretKey = (string) (config('doku.secret_key') ?: config('midtrans.server_key'));

        return hash(
            'sha512',
            $orderId.$statusCode.$grossAmount.$secretKey
        );
    }
}
