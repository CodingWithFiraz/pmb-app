<?php

namespace App\Services;

use App\Contracts\MidtransGateway;
use App\Models\Application;
use App\Models\Payment;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService implements MidtransGateway
{
    /**
     * Buat transaksi Snap lewat pustaka resmi Midtrans.
     *
     * @param  array<string, mixed>  $params
     * @return array{order_id: string, snap_token: string, snap_url: string}
     *
     * @throws Exception
     */
    public function createSnapTransaction(array $params): array
    {
        $this->applyConfig();

        $transaction = Snap::createTransaction($params);

        return [
            'order_id'   => (string) ($params['transaction_details']['order_id'] ?? ''),
            'snap_token' => (string) ($transaction->token ?? ''),
            'snap_url'   => (string) ($transaction->redirect_url ?? ''),
        ];
    }

    /**
     * Ambil token Snap yang masih berlaku, atau buat transaksi baru untuk UKT.
     */
    public function getOrCreateSnapToken(Application $application): Payment
    {
        $amount = $application->uktAmount();

        if (! $application->ukt_golongan || ! $amount) {
            throw new Exception('Golongan UKT belum ditetapkan.');
        }

        if (! config('midtrans.server_key')) {
            throw new Exception('Konfigurasi Midtrans belum lengkap (MIDTRANS_SERVER_KEY).');
        }

        $existing = $application->payments()
            ->whereNotNull('snap_token')
            ->where('golongan', $application->ukt_golongan)
            ->where('amount', $amount)
            ->whereNotIn('status', ['settlement', 'expire', 'cancel', 'deny', 'failure'])
            ->latest()
            ->first();

        if ($existing) {
            return $existing;
        }

        $application->loadMissing('user');

        $orderId = 'UKT-'.$application->id.'-'.now()->format('ymdHis').'-'
            .strtoupper(substr(md5((string) random_int(1, 999999)), 0, 4));

        $result = $this->createSnapTransaction([
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            'item_details' => [
                [
                    'id'       => 'ukt-'.$application->ukt_golongan,
                    'price'    => $amount,
                    'quantity' => 1,
                    'name'     => 'Pembayaran UKT Golongan '.$application->ukt_golongan,
                ],
            ],
            'customer_details' => [
                'first_name' => $application->full_name ?: ($application->user->name ?? 'Pendaftar'),
                'email'      => $application->user->email ?? 'pendaftar@pmb.ac.id',
                'phone'      => $application->phone ?: ($application->user->phone ?? ''),
            ],
            'enabled_payments' => ['bank_transfer', 'qris', 'gopay', 'credit_card'],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s'),
                'unit'       => 'hour',
                'duration'   => 24,
            ],
        ]);

        if ($result['snap_token'] === '') {
            throw new Exception('Midtrans tidak mengembalikan Snap token.');
        }

        return Payment::create([
            'application_id' => $application->id,
            'order_id'       => $result['order_id'] !== '' ? $result['order_id'] : $orderId,
            'golongan'       => $application->ukt_golongan,
            'amount'         => $amount,
            'status'         => 'pending',
            'snap_token'     => $result['snap_token'],
        ]);
    }

    /**
     * Perbarui status pembayaran UKT dari notifikasi Midtrans.
     *
     * @param  array<string, mixed>  $payload
     */
    public function handleNotification(array $payload): ?Payment
    {
        $orderId = (string) ($payload['order_id'] ?? '');

        if ($orderId === '') {
            return null;
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (! $payment) {
            return null;
        }

        $mapped = match ((string) ($payload['transaction_status'] ?? '')) {
            'settlement', 'capture' => 'settlement',
            'expire' => 'expire',
            'deny' => 'deny',
            'cancel' => 'cancel',
            'failure' => 'failure',
            default => 'pending',
        };

        $attributes = [
            'status' => $mapped,
        ];

        if (! empty($payload['payment_type'])) {
            $attributes['payment_type'] = $payload['payment_type'];
        }

        if (! empty($payload['transaction_id'])) {
            $attributes['midtrans_transaction_id'] = $payload['transaction_id'];
        }

        if ($mapped === 'settlement' && ! $payment->paid_at) {
            $attributes['paid_at'] = now();
        }

        $payment->update($attributes);

        if ($mapped === 'settlement') {
            $payment->application()?->update(['payment_status' => 'verified']);
        }

        return $payment->fresh();
    }

    /**
     * Terapkan kredensial Midtrans dari konfigurasi aplikasi.
     */
    private function applyConfig(): void
    {
        Config::$serverKey = (string) config('midtrans.server_key');
        Config::$clientKey = (string) config('midtrans.client_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
    }
}
