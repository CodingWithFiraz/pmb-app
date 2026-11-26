<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Snap
    |--------------------------------------------------------------------------
    |
    | Konfigurasi Midtrans untuk pembayaran tagihan PMB melalui Snap.
    | Isi kredensial sandbox pada .env, kosongkan bila belum dikonfigurasi.
    |
    */

    'server_key' => env('DOKU_SECRET_KEY', env('MIDTRANS_SERVER_KEY')),

    'client_key' => env('DOKU_CLIENT_ID', env('MIDTRANS_CLIENT_KEY')),

    'is_production' => (bool) env('DOKU_IS_PRODUCTION', env('MIDTRANS_IS_PRODUCTION', false)),

];
