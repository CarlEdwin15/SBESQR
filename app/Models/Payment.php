<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_student_id',
        'created_by',
        'payment_name',
        'amount_due',
        'date_created',
        'due_date',
        'amount_paid',
        'date_paid',
        'status',
    ];

    public function classStudent()
    {
        return $this->belongsTo(ClassStudent::class, 'class_student_id');
    }

    public function student()
    {
        return $this->hasOneThrough(
            Student::class,
            ClassStudent::class,
            'id',          // class_students.id
            'id',          // students.id
            'class_student_id', // payments.class_student_id
            'student_id'   // class_students.student_id
        );
    }

    public function class()
    {
        return $this->hasOneThrough(
            Classes::class,
            ClassStudent::class,
            'id',
            'id',
            'class_student_id',
            'class_id'
        );
    }

    public function schoolYear()
    {
        return $this->hasOneThrough(
            SchoolYear::class,
            ClassStudent::class,
            'id',
            'id',
            'class_student_id',
            'school_year_id'
        );
    }
}
