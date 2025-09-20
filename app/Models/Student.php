<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_lrn',
        'student_fName',
        'student_mName',
        'student_lName',
        'student_extName',
        'student_dob',
        'student_sex',
        'student_photo',
        'qr_code',
        'address_id',
    ];

    public function address()
    {
        return $this->belongsTo(StudentAddress::class, 'address_id');
    }

    /** 🔹 Many-to-many: a student can have multiple parents */
    public function parents()
    {
        return $this->belongsToMany(User::class, 'student_parent', 'student_id', 'parent_id')
            ->where('role', 'parent');
    }

    public function class()
    {
        return $this->belongsToMany(Classes::class, 'class_student', 'student_id', 'class_id')
            ->withPivot('school_year_id', 'enrollment_status', 'enrollment_type')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function schoolYears()
    {
        return $this->belongsToMany(SchoolYear::class, 'class_student', 'student_id', 'school_year_id')
            ->withPivot('class_id', 'enrollment_status', 'enrollment_type')
            ->withTimestamps();
    }

    public function quarterlyGrades()
    {
        return $this->hasMany(QuarterlyGrade::class);
    }

    public function finalSubjectGrades()
    {
        return $this->hasMany(FinalSubjectGrade::class);
    }

    public function generalAverages()
    {
        return $this->hasMany(GeneralAverage::class);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->student_lName}, {$this->student_fName} {$this->student_mName} {$this->student_extName}");
    }

    public function getGenderAttribute()
    {
        return ucfirst(strtolower($this->student_sex));
    }
}
