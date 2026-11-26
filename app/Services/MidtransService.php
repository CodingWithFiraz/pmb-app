<?php

namespace App\Services;

use App\Contracts\MidtransGateway;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MidtransService implements MidtransGateway
{
    /**
     * Buat transaksi pembayaran melalui DOKU Jokul Checkout API.
     *
     * @param  array<string, mixed>  $params
     * @return array{order_id: string, snap_token: string, snap_url: string}
     *
     * @throws Exception
     */
    public function createSnapTransaction(array $params): array
    {
        $clientId = (string) (config('doku.client_id') ?: config('midtrans.client_key'));
        $secretKey = (string) (config('doku.secret_key') ?: config('midtrans.server_key'));
        $isProduction = (bool) (config('doku.is_production') ?? config('midtrans.is_production'));

        $orderId = (string) ($params['transaction_details']['order_id'] ?? '');
        $grossAmount = (int) ($params['transaction_details']['gross_amount'] ?? 0);

        $baseUrl = $isProduction
            ? 'https://api.doku.com'
            : 'https://api-sandbox.doku.com';
        $targetPath = '/checkout/v1/payment';

        $paymentMethodTypes = $params['doku_payment_method_types']
            ?? config('doku.payment_method_types', [
                'VIRTUAL_ACCOUNT_BCA',
                'VIRTUAL_ACCOUNT_BANK_MANDIRI',
                'VIRTUAL_ACCOUNT_BNI',
                'VIRTUAL_ACCOUNT_BRI',
                'VIRTUAL_ACCOUNT_BANK_SYARIAH_INDONESIA',
                'VIRTUAL_ACCOUNT_PERMATA',
                'VIRTUAL_ACCOUNT_CIMB',
                'VIRTUAL_ACCOUNT_DANAMON',
                'VIRTUAL_ACCOUNT_DOKU',
                'ONLINE_TO_OFFLINE_ALFA',
                'ONLINE_TO_OFFLINE_INDOMARET',
            ]);

        $lineItems = collect($params['item_details'] ?? [])->map(fn (array $item): array => [
            'id'       => (string) ($item['id'] ?? 'item'),
            'name'     => (string) ($item['name'] ?? 'Pembayaran PMB'),
            'price'    => (int) ($item['price'] ?? $grossAmount),
            'quantity' => (int) ($item['quantity'] ?? 1),
        ])->values()->all();

        $customer = [
            'id'    => (string) ($params['customer_details']['email'] ?? $orderId),
            'name'  => (string) ($params['customer_details']['first_name'] ?? 'Pendaftar'),
            'email' => (string) ($params['customer_details']['email'] ?? ''),
        ];

        $rawPhone = preg_replace('/\D+/', '', (string) ($params['customer_details']['phone'] ?? ''));
        if ($rawPhone !== '' && strlen($rawPhone) <= 20) {
            $customer['phone'] = $rawPhone;
        }

        $body = [
            'order' => [
                'amount'         => $grossAmount,
                'invoice_number' => $orderId,
                'currency'       => 'IDR',
                'callback_url'   => route('pendaftar.pembayaran'),
                'line_items'     => $lineItems,
            ],
            'payment' => [
                'payment_due_date'     => 60,
                'payment_method_types' => $paymentMethodTypes,
            ],
            'customer' => $customer,
            'additional_info' => [
                'override_notification_url' => route('doku.callback'),
            ],
        ];

        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $digest = base64_encode(hash('sha256', $jsonBody, true));
        $requestId = (string) Str::uuid();
        $timestamp = Carbon::now('UTC')->format('Y-m-d\TH:i:s\Z');

        $componentSignature = "Client-Id:{$clientId}\n"
            ."Request-Id:{$requestId}\n"
            ."Request-Timestamp:{$timestamp}\n"
            ."Request-Target:{$targetPath}\n"
            ."Digest:{$digest}";

        $signature = 'HMACSHA256='.base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

        $response = Http::withHeaders([
            'Client-Id'         => $clientId,
            'Request-Id'        => $requestId,
            'Request-Timestamp' => $timestamp,
            'Signature'         => $signature,
            'Content-Type'      => 'application/json',
        ])->withBody($jsonBody, 'application/json')->post($baseUrl.$targetPath);

        if (! $response->successful()) {
            $errorMsg = $response->json('error.message')
                ?? $response->json('message.0')
                ?? $response->body();
            throw new Exception('DOKU Checkout Error: '.$errorMsg);
        }

        $paymentData = $response->json('response.payment') ?? [];

        return [
            'order_id'   => $orderId,
            'snap_token' => (string) ($paymentData['token_id'] ?? $paymentData['token'] ?? ''),
            'snap_url'   => (string) ($paymentData['url'] ?? ''),
        ];
    }

    /**
     * Cek status transaksi langsung ke DOKU Check Status API (/orders/v1/status/{invoice_number}).
     * Sangat berguna ketika aplikasi berjalan di localhost (127.0.0.1) di mana webhook DOKU
     * tidak bisa menjangkau server lokal, atau saat terjadi jeda pengiriman webhook.
     *
     * @return array{status: string, channel: string|null}|null
     */
    public function checkTransactionStatus(string $orderId): ?array
    {
        $clientId = (string) (config('doku.client_id') ?: config('midtrans.client_key'));
        $secretKey = (string) (config('doku.secret_key') ?: config('midtrans.server_key'));
        $isProduction = (bool) (config('doku.is_production') ?? config('midtrans.is_production'));

        if ($clientId === '' || $secretKey === '' || $orderId === '') {
            return null;
        }

        $baseUrl = $isProduction
            ? 'https://api.doku.com'
            : 'https://api-sandbox.doku.com';
        $targetPath = '/orders/v1/status/'.rawurlencode($orderId);

        $requestId = (string) Str::uuid();
        $timestamp = Carbon::now('UTC')->format('Y-m-d\TH:i:s\Z');

        // Untuk GET request tanpa body, Signature DOKU tidak menyertakan baris Digest
        $componentSignature = "Client-Id:{$clientId}\n"
            ."Request-Id:{$requestId}\n"
            ."Request-Timestamp:{$timestamp}\n"
            ."Request-Target:{$targetPath}";

        $signature = 'HMACSHA256='.base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

        try {
            $response = Http::timeout(8)->withHeaders([
                'Client-Id'         => $clientId,
                'Request-Id'        => $requestId,
                'Request-Timestamp' => $timestamp,
                'Signature'         => $signature,
            ])->get($baseUrl.$targetPath);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json() ?? [];
            $rawStatus = strtolower((string) ($data['transaction']['status'] ?? ''));

            if ($rawStatus === '') {
                return null;
            }

            $mappedStatus = match ($rawStatus) {
                'success', 'settlement', 'capture'    => 'paid',
                'expired', 'expire'                   => 'expired',
                'failed', 'deny', 'cancel', 'failure' => 'failed',
                default                               => 'pending',
            };

            $channel = $data['channel']['id']
                ?? $data['acquirer']['id']
                ?? $data['service']['id']
                ?? null;

            return [
                'status'  => $mappedStatus,
                'channel' => $channel ? 'doku - '.strtolower((string) $channel) : null,
            ];
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }
}
