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

    /**
     * Kode golongan UKT berdasarkan penghasilan orang tua per bulan.
     */
    public static function kodeDariPenghasilan(int $penghasilan): string
    {
        foreach (config('ukt.penghasilan', []) as $rentang) {
            $min = (int) $rentang['min'];
            $max = $rentang['max'];

            if ($penghasilan < $min) {
                continue;
            }

            if ($max !== null && $penghasilan > (int) $max) {
                continue;
            }

            return (string) $rentang['kode'];
        }

        return 'V';
    }

    public static function dariPenghasilan(int $penghasilan): self
    {
        return static::query()
            ->where('kode', static::kodeDariPenghasilan($penghasilan))
            ->firstOrFail();
    }

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
