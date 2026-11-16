<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';

    protected $fillable = [
        'fakultas_id', 'nama', 'kode', 'slug', 'jenjang', 'deskripsi', 'kuota',
    ];

    protected $casts = [
        'kuota' => 'integer',
    ];

    /**
     * Routing publik memakai slug, bukan id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function tarifUkt(): HasMany
    {
        return $this->hasMany(TarifUkt::class, 'prodi_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'prodi_1_id');
    }

    public function pendaftaranAlternatif(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'prodi_2_id');
    }
}
