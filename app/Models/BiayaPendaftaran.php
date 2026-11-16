<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiayaPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'biaya_pendaftaran';

    protected $fillable = [
        'periode_id', 'jalur_id', 'nominal',
    ];

    protected $casts = [
        'nominal' => 'integer',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePmb::class, 'periode_id');
    }

    public function jalur(): BelongsTo
    {
        return $this->belongsTo(JalurMasuk::class, 'jalur_id');
    }
}
