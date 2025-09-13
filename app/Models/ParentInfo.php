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
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    // Parent's phone conversion from +63 to 09
    public function getFatherPhoneAttribute($value)
    {
        return $this->formatForDisplay($value);
    }

    public function getMotherPhoneAttribute($value)
    {
        return $this->formatForDisplay($value);
    }

    public function getEmergcontPhoneAttribute($value)
    {
        return $this->formatForDisplay($value);
    }

    private function formatForDisplay($phone)
    {
        if (!$phone) return null;

        // If starts with +639, display as 09
        if (str_starts_with($phone, '+639')) {
            return '0' . substr($phone, 3);
        }

        return $phone;
    }
}
