<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Many-to-Many relationship:
     * A Subject can belong to many classes in different school years.
     */
    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_subject')
            ->withPivot('school_year_id')
            ->withTimestamps();
    }

    public function schoolYears()
    {
        return $this->belongsToMany(SchoolYear::class, 'class_subject')
            ->withPivot('class_id')
            ->withTimestamps();
    }
}
