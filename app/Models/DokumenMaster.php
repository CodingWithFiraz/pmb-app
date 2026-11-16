<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DokumenMaster extends Model
{
    use HasFactory;

    protected $table = 'dokumen_master';

    protected $fillable = [
        'nama', 'kode', 'tipe_file', 'max_size', 'is_wajib',
    ];

    protected $casts = [
        'max_size' => 'integer',
        'is_wajib' => 'boolean',
    ];

    public function jalur(): BelongsToMany
    {
        return $this->belongsToMany(JalurMasuk::class, 'dokumen_jalur', 'dokumen_id', 'jalur_id')
            ->withTimestamps();
    }

    public function dokumenJalur(): HasMany
    {
        return $this->hasMany(DokumenJalur::class, 'dokumen_id');
    }

    public function dokumenPendaftar(): HasMany
    {
        return $this->hasMany(DokumenPendaftar::class, 'dokumen_id');
    }
}
