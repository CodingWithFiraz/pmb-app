<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'application_id',
        'order_id',
        'golongan',
        'amount',
        'status',
        'snap_token',
        'payment_type',
        'midtrans_transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'golongan' => 'integer',
        'amount'   => 'integer',
        'paid_at'  => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'settlement';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'unpaid'     => 'Belum Dibayar',
            'pending'    => 'Menunggu Pembayaran',
            'settlement' => 'Lunas',
            'expire'     => 'Kadaluarsa',
            'cancel'     => 'Dibatalkan',
            'deny'       => 'Ditolak',
            'failure'    => 'Gagal',
            default      => $this->status,
        };
    }
}
