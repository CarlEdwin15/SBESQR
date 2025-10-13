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

    public function histories()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->histories()->sum('amount_paid');
    }

    public function getRemainingAmountAttribute()
    {
        return max($this->amount_due - $this->total_paid, 0);
    }

    public function getStatusAttribute($value)
    {
        $totalPaid = $this->total_paid;
        if ($totalPaid <= 0) return 'unpaid';
        if ($totalPaid < $this->amount_due) return 'partial';
        return 'paid';
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class, 'payment_id');
    }

    public function latestPaymentHistory()
    {
        return $this->paymentHistories()->orderByDesc('payment_date')->first();
    }

    public function latestPaymentMethod()
    {
        $latest = $this->latestPaymentHistory();
        return $latest ? $latest->payment_method_name : null;
    }

    public function latestPaymentDate()
    {
        return $this->latestPaymentHistory()?->payment_date;
    }
}
