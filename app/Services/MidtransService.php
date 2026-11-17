<?php

namespace App\Services;

use App\Contracts\MidtransGateway;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;

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
        if (!isset($params['expiry'])) {
            $params['expiry'] = [
                'start_time' => Carbon::now()->format('Y-m-d H:i:s O'),
                'unit' => 'minutes',
                'duration' => 60,
            ];
        }

        $transaction = Snap::createTransaction($params);

        return [
            'order_id'   => (string) ($params['transaction_details']['order_id'] ?? ''),
            'snap_token' => (string) ($transaction->token ?? ''),
            'snap_url'   => (string) ($transaction->redirect_url ?? ''),
        ];
    }

 
    private function applyConfig(): void
    {
        Config::$serverKey = (string) config('midtrans.server_key');
        Config::$clientKey = (string) config('midtrans.client_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
    }
}
