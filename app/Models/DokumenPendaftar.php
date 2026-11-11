<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenPendaftar extends Model
{
    use HasFactory;

    protected $table = 'dokumen_pendaftar';

    protected $fillable = [
        'pendaftaran_id', 'dokumen_id', 'file_path', 'status', 'catatan_admin',
    ];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(DokumenMaster::class, 'dokumen_id');
    }
}
