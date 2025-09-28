<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    protected $table = 'class_student';

    protected $fillable = [
        'student_id',
        'class_id',
        'school_year_id',
        'enrollment_status',
        'enrollment_type',
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
}
