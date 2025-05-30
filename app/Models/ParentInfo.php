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
        'emergCont_fName',
        'emergCont_mName',
        'emergCont_lName',
        'emergCont_phone',
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'parent_id');
    }
}

