<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JalurMasuk extends Model
{
    use HasFactory;

    protected $table = 'jalur_masuk';

    protected $fillable = [
        'nama', 'kode', 'deskripsi', 'urutan', 'punya_biaya_pendaftaran',
    ];

    protected $casts = [
        'urutan'                    => 'integer',
        'punya_biaya_pendaftaran'   => 'boolean',
    ];

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'jalur_id');
    }

    public function tarifUkt(): HasMany
    {
        return $this->hasMany(TarifUkt::class, 'jalur_id');
    }

    public function biayaPendaftaran(): HasMany
    {
        return $this->hasMany(BiayaPendaftaran::class, 'jalur_id');
    }

    public function dokumenJalur(): HasMany
    {
        return $this->hasMany(DokumenJalur::class, 'jalur_id');
    }

    public function dokumen(): BelongsToMany
    {
        return $this->belongsToMany(DokumenMaster::class, 'dokumen_jalur', 'jalur_id', 'dokumen_id')
            ->withTimestamps();
    }
}
