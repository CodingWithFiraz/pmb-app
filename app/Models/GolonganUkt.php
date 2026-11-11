<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GolonganUkt extends Model
{
    use HasFactory;

    protected $table = 'golongan_ukt';

    protected $fillable = [
        'kode', 'nama', 'deskripsi',
    ];

    public function tarifUkt(): HasMany
    {
        return $this->hasMany(TarifUkt::class, 'golongan_id');
    }

    public function pendaftarProfiles(): HasMany
    {
        return $this->hasMany(PendaftarProfile::class, 'golongan_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'golongan_id');
    }
}
