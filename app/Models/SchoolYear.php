<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $fillable = [
        'school_year',
        'start_date',
        'end_date',
    ];

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_student', 'student_id', 'class_id')
            ->withPivot('enrollement_status', 'enrollment_type', 'school_year_id')
            ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
            ->withPivot('role', 'school_year_id')
            ->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id')
            ->withPivot('enrollement_status', 'enrollment_type', 'school_year_id')
            ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'school_year_id');
    }
}
