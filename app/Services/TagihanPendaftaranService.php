<?php

namespace App\Services;

use App\Models\BiayaPendaftaran;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\TarifUkt;

class TagihanPendaftaranService
{
    /**
     * Buat tagihan otomatis saat pendaftaran diverifikasi.
     *
     * - Jalur yang memiliki biaya pendaftaran mendapat tagihan "pendaftaran".
     * - Seluruh jalur mendapat tagihan "ukt" sesuai tarif pada periode, jalur,
     *   program studi pilihan pertama, dan golongan UKT pendaftar.
     *
     * Tagihan dibuat berstatus pending dan tidak diduplikasi bila proses
     * verifikasi dijalankan lebih dari sekali.
     *
     * @return array<int, Pembayaran>
     */
    public function buat(Pendaftaran $pendaftaran): array
    {
        $tagihan = [];

        if ($pendaftaran->jalur && $pendaftaran->jalur->punya_biaya_pendaftaran) {
            $nominal = BiayaPendaftaran::query()
                ->where('periode_id', $pendaftaran->periode_id)
                ->where('jalur_id', $pendaftaran->jalur_id)
                ->value('nominal');

            if ($nominal !== null) {
                $tagihan[] = Pembayaran::firstOrCreate(
                    [
                        'pendaftaran_id' => $pendaftaran->id,
                        'jenis'          => 'pendaftaran',
                    ],
                    [
                        'nominal' => (int) $nominal,
                        'status'  => 'pending',
                    ]
                );
            }
        }

        $ukt = TarifUkt::query()
            ->where('periode_id', $pendaftaran->periode_id)
            ->where('jalur_id', $pendaftaran->jalur_id)
            ->where('prodi_id', $pendaftaran->prodi_1_id)
            ->where('golongan_id', $pendaftaran->golongan_id)
            ->value('nominal');

        if ($ukt !== null) {
            $tagihan[] = Pembayaran::firstOrCreate(
                [
                    'pendaftaran_id' => $pendaftaran->id,
                    'jenis'          => 'ukt',
                ],
                [
                    'nominal' => (int) $ukt,
                    'status'  => 'pending',
                ]
            );
        }

        return $tagihan;
    }
}
