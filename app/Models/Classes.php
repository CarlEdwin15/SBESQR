<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'grade_level',
        'section',
    ];

    public function getFormattedGradeLevelAttribute()
    {
        return match ($this->grade_level) {
            'kindergarten' => 'Kindergarten',
            'grade1' => 'Grade 1',
            'grade2' => 'Grade 2',
            'grade3' => 'Grade 3',
            'grade4' => 'Grade 4',
            'grade5' => 'Grade 5',
            'grade6' => 'Grade 6',
            default => ucfirst($this->grade_level)
        };
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }
}
