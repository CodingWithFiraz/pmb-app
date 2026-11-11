<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas';

    protected $fillable = [
        'nama', 'kode', 'slug', 'deskripsi',
    ];

    /**
     * Routing publik memakai slug, bukan id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function prodi(): HasMany
    {
        return $this->hasMany(Prodi::class, 'fakultas_id');
    }
}
