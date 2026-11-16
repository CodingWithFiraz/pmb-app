<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarifUkt extends Model
{
    use HasFactory;

    protected $table = 'tarif_ukt';

    protected $fillable = [
        'periode_id', 'jalur_id', 'prodi_id', 'golongan_id', 'nominal',
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

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function golongan(): BelongsTo
    {
        return $this->belongsTo(GolonganUkt::class, 'golongan_id');
    }
}
