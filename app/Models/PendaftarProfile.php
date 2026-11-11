<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PendaftarProfile extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_profiles';

    /**
     * Field yang wajib lengkap agar profil dianggap 100%.
     *
     * @var array<int, string>
     */
    public const REQUIRED_FIELDS = [
        'nik',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'provinsi',
        'kota',
        'asal_sekolah',
        'tahun_lulus',
        'nama_ortu',
        'pekerjaan_ortu',
        'penghasilan_ortu',
        'golongan_id',
    ];

    protected $fillable = [
        'user_id', 'nik', 'nisn', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'alamat', 'provinsi', 'kota', 'asal_sekolah',
        'tahun_lulus', 'nama_ortu', 'pekerjaan_ortu', 'penghasilan_ortu',
        'golongan_id',
    ];

    protected $casts = [
        'tanggal_lahir'    => 'date',
        'tahun_lulus'      => 'integer',
        'penghasilan_ortu' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function golongan(): BelongsTo
    {
        return $this->belongsTo(GolonganUkt::class, 'golongan_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'user_id', 'user_id');
    }

    /**
     * Field wajib yang sudah terisi.
     *
     * @return array<int, string>
     */
    public function filledFields(): array
    {
        return array_values(array_filter(
            self::REQUIRED_FIELDS,
            fn (string $field): bool => filled($this->{$field})
        ));
    }

    /**
     * Persentase kelengkapan profil (0–100).
     */
    public function completionPercentage(): int
    {
        if (! $this->exists) {
            return 0;
        }

        return (int) round(count($this->filledFields()) / count(self::REQUIRED_FIELDS) * 100);
    }

    public function isComplete(): bool
    {
        return $this->completionPercentage() === 100;
    }
}
