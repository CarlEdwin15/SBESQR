<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    protected $table = 'class_student';

    // In ClassStudent model
    protected $fillable = [
        'student_id',
        'class_id',
        'school_year_id',
        'enrollment_status',
        'enrollment_type',
        'q1_allow_view',
        'q2_allow_view',
        'q3_allow_view',
        'q4_allow_view',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Check if grade viewing is allowed for a specific quarter
    public function isGradeViewingAllowed($quarter)
    {
        return match ($quarter) {
            1 => $this->q1_allow_view,
            2 => $this->q2_allow_view,
            3 => $this->q3_allow_view,
            4 => $this->q4_allow_view,
            default => false
        };
    }
}
