<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodePmb extends Model
{
    use HasFactory;

    protected $table = 'periode_pmb';

    protected $fillable = [
        'nama', 'tahun_akademik', 'tanggal_mulai', 'tanggal_selesai', 'is_active',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_active'        => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getActive(): ?self
    {
        return static::active()->first();
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'periode_id');
    }

    public function tarifUkt(): HasMany
    {
        return $this->hasMany(TarifUkt::class, 'periode_id');
    }

    public function biayaPendaftaran(): HasMany
    {
        return $this->hasMany(BiayaPendaftaran::class, 'periode_id');
    }
}
