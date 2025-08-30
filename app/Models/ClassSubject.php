<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSubject extends Model
{
    use HasFactory;

    protected $table = 'class_subject';

    protected $fillable = [
        'class_id',
        'subject_id',
        'school_year_id',
        'teacher_id',
        'description',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }


    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function quarters()
    {
        return $this->hasMany(Quarter::class);
    }

    /**
     * Auto-create 4 quarters when a class_subject is created.
     */
    protected static function booted()
    {
        static::created(function ($classSubject) {
            foreach ([1, 2, 3, 4] as $q) {
                $classSubject->quarters()->firstOrCreate([
                    'quarter' => $q,
                ], [
                    'status' => 'upcoming',
                ]);
            }
        });
    }
}
