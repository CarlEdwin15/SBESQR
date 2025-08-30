<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'quarter_id',
        'written_work',
        'performance_task',
        'quarterly_exam',
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
}
