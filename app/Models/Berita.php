<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'judul', 'slug', 'image_url', 'deskripsi', 'author', 'published_at', 'status',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Routing publik memakai slug, bukan id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublish(Builder $query): Builder
    {
        return $query->where('status', 'publish');
    }

    /**
     * Berita yang benar-benar tayang di halaman publik:
     * berstatus publish dan tanggal publikasinya sudah lewat.
     */
    public function scopeTayang(Builder $query): Builder
    {
        return $query->where('status', 'publish')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isTayang(): bool
    {
        return $this->status === 'publish'
            && $this->published_at !== null
            && $this->published_at->lessThanOrEqualTo(now());
    }
}
