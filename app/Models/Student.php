<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // protected $primaryKey = 'student_id';

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
        'class_id',
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
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function schoolYears()
    {
        return $this->belongsToMany(SchoolYear::class, 'school_year_student');
    }

    public function getFullNameAttribute()
    {
        return "{$this->student_lName}, {$this->student_fName} {$this->student_mName} {$this->student_extName}";
    }

    public function getGenderAttribute()
    {
        return ucfirst(strtolower($this->student_sex));
    }
}
