<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    protected $fillable = [
        'key', 'value',
    ];

    /**
     * Ambil nilai pengaturan berdasarkan key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Ambil beberapa nilai pengaturan sekaligus.
     *
     * @param  array<int, string>  $keys
     * @return array<string, mixed>
     */
    public static function many(array $keys, mixed $default = null): array
    {
        $values = static::whereIn('key', $keys)->pluck('value', 'key')->all();

        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $values[$key] ?? $default;
        }

        return $result;
    }
}
