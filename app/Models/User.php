<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'firstName',
        'lastName',
        'middleName',
        'extName',
        'email',
        'gender',
        'phone',
        'house_no',
        'street_name',
        'barangay',
        'municipality_city',
        'province',
        'country',
        'zip_code',
        'dob',
        'password',
        'profile_photo',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function classes()
{
    return $this->belongsToMany(Classes::class, 'class_user', 'user_id', 'class_id')
        ->withPivot('role', 'school_year_id');
}

    public function advisoryClasses()
    {
        return $this->classes()->wherePivotIn('role', ['adviser']);
    }

    public function subjectClasses()
    {
        return $this->classes()->wherePivotIn('role', ['subject_teacher']);
    }

    public function schoolYears()
    {
        return $this->belongsToMany(Student::class, 'class_user')
            ->withPivot('school_year_id')
            ->withTimestamps();
    }
}
