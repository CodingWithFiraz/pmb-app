<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionPeriod extends Model
{
    protected $fillable = [
        'name', 'academic_year', 'starts_at', 'ends_at',
        'registration_fee', 'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function isOpen(): bool
    {
        if ($this->status !== 'open') {
            return false;
        }

        if ($this->ends_at && now()->gt($this->ends_at)) {
            return false;
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public static function getActive()
    {
        $period = static::active()->first();
        if ($period) {
            return $period;
        }

        return static::where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->first();
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}