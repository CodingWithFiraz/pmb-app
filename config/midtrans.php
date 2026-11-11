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

    'server_key' => env('MIDTRANS_SERVER_KEY'),

    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),

];
