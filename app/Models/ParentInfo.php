<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentInfo extends Model
{
    protected $table = 'parent_info';

    protected $fillable = [
        'father_fName',
        'father_mName',
        'father_lName',
        'father_phone',
        'mother_fName',
        'mother_mName',
        'mother_lName',
        'mother_phone',
        'emergcont_fName',
        'emergcont_mName',
        'emergcont_lName',
        'emergcont_phone',
        'parent_email',
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'parent_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
