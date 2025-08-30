<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinalSubjectGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_subject_id',
        'final_average',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }
}
