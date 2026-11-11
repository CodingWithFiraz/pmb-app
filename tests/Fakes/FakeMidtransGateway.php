<?php

namespace Tests\Fakes;

use App\Contracts\MidtransGateway;
use RuntimeException;

class FakeMidtransGateway implements MidtransGateway
{
    /**
     * Parameter terakhir yang dikirim ke Midtrans.
     *
     * @var array<string, mixed>
     */
    public array $params = [];

    public bool $shouldFail = false;

    public function createSnapTransaction(array $params): array
    {
        if ($this->shouldFail) {
            throw new RuntimeException('Midtrans sandbox sedang tidak dapat diakses.');
        }

        $this->params = $params;

        $orderId = (string) $params['transaction_details']['order_id'];
        $token = 'snap-token-'.substr(md5($orderId), 0, 12);

        return [
            'order_id'   => $orderId,
            'snap_token' => $token,
            'snap_url'   => 'https://app.midtrans.com/snap/v2/transactions/'.$token.'/pay',
        ];
    }
}
