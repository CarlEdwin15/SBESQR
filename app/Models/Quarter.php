<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quarter extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_subject_id',
        'quarter',
        'start_date',
        'end_date',
        'status',
    ];

    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }

    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class);
    }
}
