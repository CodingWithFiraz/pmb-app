<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DOKU Jokul Checkout
    |--------------------------------------------------------------------------
    |
    | Konfigurasi DOKU untuk pembayaran tagihan PMB melalui Jokul Checkout
    | (QRIS, E-Wallet, Bank Account / Transfer, dan Virtual Account).
    |
    */

    'client_id' => env('DOKU_CLIENT_ID', env('MIDTRANS_CLIENT_KEY')),

    'secret_key' => env('DOKU_SECRET_KEY', env('MIDTRANS_SERVER_KEY')),

    'is_production' => (bool) env('DOKU_IS_PRODUCTION', env('MIDTRANS_IS_PRODUCTION', false)),

    'payment_method_types' => [
        // Virtual Account (Aktif untuk Payment Real)
        'VIRTUAL_ACCOUNT_BCA',
        'VIRTUAL_ACCOUNT_BANK_MANDIRI',
        'VIRTUAL_ACCOUNT_BNI',
        'VIRTUAL_ACCOUNT_BRI',
        'VIRTUAL_ACCOUNT_BANK_SYARIAH_INDONESIA',
        'VIRTUAL_ACCOUNT_PERMATA',
        'VIRTUAL_ACCOUNT_CIMB',
        'VIRTUAL_ACCOUNT_DANAMON',
        'VIRTUAL_ACCOUNT_DOKU',
        // Convenience Store / Gerai Retail (Aktif untuk Payment Real)
        'ONLINE_TO_OFFLINE_ALFA',
        'ONLINE_TO_OFFLINE_INDOMARET',
    ],

    'coming_soon_methods' => [
        'qris',
        'ewallet',
        'bank_account',
    ],

];
