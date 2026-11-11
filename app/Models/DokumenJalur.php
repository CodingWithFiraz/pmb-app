<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenJalur extends Model
{
    use HasFactory;

    protected $table = 'dokumen_jalur';

    protected $fillable = [
        'dokumen_id', 'jalur_id',
    ];

    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(DokumenMaster::class, 'dokumen_id');
    }

    public function jalur(): BelongsTo
    {
        return $this->belongsTo(JalurMasuk::class, 'jalur_id');
    }
}
