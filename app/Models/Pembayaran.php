<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pendaftaran_id', 'jenis', 'nominal', 'status', 'order_id', 'snap_token',
        'snap_url', 'qris_url', 'metode', 'bukti_path', 'catatan_admin', 'paid_at',
    ];

    protected $casts = [
        'nominal'  => 'integer',
        'paid_at'  => 'datetime',
    ];

    /**
     * Label status pembayaran untuk tampilan.
     *
     * @var array<string, string>
     */
    public const STATUS_LABELS = [
        'pending' => 'Menunggu Pembayaran',
        'paid'    => 'Lunas',
        'expired' => 'Kedaluwarsa',
        'failed'  => 'Gagal',
    ];

    /**
     * Label jenis tagihan untuk tampilan.
     *
     * @var array<string, string>
     */
    public const JENIS_LABELS = [
        'pendaftaran' => 'Biaya Pendaftaran',
        'ukt'         => 'Uang Kuliah Tunggal (UKT)',
    ];

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function jenisLabel(): string
    {
        return self::JENIS_LABELS[$this->jenis] ?? ucfirst((string) $this->jenis);
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
