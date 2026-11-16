<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id', 'periode_id', 'jalur_id', 'prodi_1_id', 'prodi_2_id',
        'golongan_id', 'status', 'nomor_pendaftaran', 'verified_by',
        'verified_at', 'hasil_kelulusan', 'catatan_admin',
    ];

    protected $casts = [
        'verified_at'     => 'datetime',
        'hasil_kelulusan' => 'string',
    ];

    /**
     * Label status pendaftaran untuk tampilan.
     *
     * @var array<string, string>
     */
    public const STATUS_LABELS = [
        'draft'              => 'Draft',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'terverifikasi'      => 'Terverifikasi',
        'ditolak'            => 'Ditolak',
    ];

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Pilihan jalur/prodi hanya bisa diubah selama pendaftaran masih draft.
     */
    public function pilihanTerkunci(): bool
    {
        return ! $this->isDraft();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePmb::class, 'periode_id');
    }

    public function jalur(): BelongsTo
    {
        return $this->belongsTo(JalurMasuk::class, 'jalur_id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_1_id');
    }

    public function prodiAlternatif(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_2_id');
    }

    public function golongan(): BelongsTo
    {
        return $this->belongsTo(GolonganUkt::class, 'golongan_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenPendaftar::class, 'pendaftaran_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'pendaftaran_id');
    }
}
