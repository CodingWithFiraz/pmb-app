<?php

return [
    'golongan' => [
        1 => 500000,
        2 => 1000000,
        3 => 2500000,
        4 => 4500000,
        5 => 7500000,
    ],

    'labels' => [
        1 => 'Golongan I',
        2 => 'Golongan II',
        3 => 'Golongan III',
        4 => 'Golongan IV',
        5 => 'Golongan V',
    ],

    'descriptions' => [
        1 => 'Kemampuan ekonomi paling rendah',
        2 => 'Kemampuan ekonomi menengah bawah',
        3 => 'Kemampuan ekonomi menengah',
        4 => 'Kemampuan ekonomi menengah atas',
        5 => 'Kemampuan ekonomi paling tinggi',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pemetaan penghasilan orang tua → kode golongan UKT
    |--------------------------------------------------------------------------
    |
    | Batas atas bersifat inklusif. Golongan IV tidak ditetapkan otomatis.
    | Pendaftar tidak boleh memilih golongan; sistem menghitung dari nominal.
    |
    */
    'penghasilan' => [
        ['kode' => 'I',   'min' => 0,         'max' => 2_999_999],
        ['kode' => 'II',  'min' => 3_000_000, 'max' => 4_999_999],
        ['kode' => 'III', 'min' => 5_000_000, 'max' => 8_999_999],
        ['kode' => 'V',   'min' => 9_000_000, 'max' => null],
    ],
];
