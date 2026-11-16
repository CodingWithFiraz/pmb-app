<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $fillable = [
        'code', 'name', 'faculty', 'description', 'quota', 'is_active',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}