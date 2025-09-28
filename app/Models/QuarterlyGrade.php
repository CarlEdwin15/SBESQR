<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuarterlyGrade extends Model
{
    use HasFactory;

    protected $table = 'quarterly_grades';

    protected $fillable = [
        'student_id',
        'quarter_id',
        'final_grade',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function quarter()
    {
        return $this->belongsTo(Quarter::class);
    }

    public function classStudent()
    {
        return $this->belongsTo(ClassStudent::class);
    }
}
