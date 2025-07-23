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
        'parent_id',
    ];

    public function address()
    {
        return $this->belongsTo(StudentAddress::class, 'address_id');
    }

    public function parentInfo()
    {
        return $this->belongsTo(ParentInfo::class, 'parent_id');
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
        return $this->belongsToMany(SchoolYear::class, 'class_student', 'class_id', 'student_id')
            ->withPivot('school_year_id', 'enrollment_status', 'enrollment_type')
            ->withTimestamps();
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
