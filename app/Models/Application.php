<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'admission_period_id', 'study_program_id',
        'registration_number', 'full_name', 'nik', 'gender',
        'birth_date', 'birth_place', 'religion', 'phone', 'address',
        'city', 'province', 'postal_code', 'school_name', 'school_city',
        'major', 'graduation_year', 'gpa', 'status', 'payment_status',
        'submitted_at', 'reviewed_by', 'admin_note',
    ];

    protected $casts = [
        'birth_date'   => 'date',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admissionPeriod()
    {
        return $this->belongsTo(AdmissionPeriod::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(ApplicationStatusHistory::class)->latest();
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'revision_required']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'             => 'Draft',
            'submitted'         => 'Menunggu Pemeriksaan',
            'under_review'      => 'Sedang Diperiksa',
            'revision_required' => 'Perlu Revisi',
            'passed'            => 'Lulus',
            'not_passed'        => 'Tidak Lulus',
            default             => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'             => 'gray',
            'submitted'         => 'blue',
            'under_review'      => 'yellow',
            'revision_required' => 'orange',
            'passed'            => 'green',
            'not_passed'        => 'red',
            default             => 'gray',
        };
    }
}