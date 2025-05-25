<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAddress extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'house_no',
        'street_name',
        'barangay',
        'municipality_city',
        'province',
        'country',
        'zip_code',
        'pob',
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'address_id');
    }
}

