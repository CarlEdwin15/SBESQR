<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'class_id',
        'school_year_id',
        'student_id',
        'payment_name',
        'amount_due',
        'date_created',
        'due_date',
        'amount_paid',
        'date_paid',
        'status',
        'remarks',
    ];

    /** 🔹 Relations */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function scopeForYear($query, $year)
    {
        return $query->whereHas('schoolYear', function ($q) use ($year) {
            $q->where('year', $year); // or ->where('name', $year)
        });
    }
}
