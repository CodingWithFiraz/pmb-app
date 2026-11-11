<?php

namespace App\Contracts;

interface MidtransGateway
{
    /**
     * Buat transaksi Snap baru di Midtrans.
     *
     * @param  array<string, mixed>  $params
     * @return array{order_id: string, snap_token: string, snap_url: string}
     */
    public function createSnapTransaction(array $params): array;
}
