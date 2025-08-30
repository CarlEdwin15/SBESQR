<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, HasPushSubscriptions;

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

    /** 🔑 Full name accessor */
    public function getFullNameAttribute()
    {
        $middle = $this->middleName ? " {$this->middleName}" : '';
        $ext    = $this->extName ? " {$this->extName}" : '';

        return "{$this->firstName}{$middle} {$this->lastName}{$ext}";
    }

    /** 🔑 Classes the teacher is assigned to (via class_user pivot) */
    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_user', 'user_id', 'class_id')
            ->withPivot('role', 'school_year_id')
            ->withTimestamps();
    }

    /** 🔑 Advisory role */
    public function advisoryClasses()
    {
        return $this->classes()->wherePivot('role', 'adviser');
    }

    /** 🔑 Subject teaching role */
    public function subjectClasses()
    {
        return $this->classes()->wherePivot('role', 'subject_teacher');
    }

    /** 🔑 School years where teacher is active */
    public function schoolYears()
    {
        return $this->belongsToMany(SchoolYear::class, 'class_user', 'user_id', 'school_year_id')
            ->withPivot('class_id', 'role')
            ->withTimestamps();
    }

    /** 🔑 Subjects taught by this teacher through class_subject */
    public function classSubjects()
    {
        return $this->hasManyThrough(
            ClassSubject::class,
            Classes::class,
            'id',            // Classes PK
            'class_id',      // ClassSubject FK
            'id',            // User PK
            'id'             // Classes PK
        );
    }

    /** 🔑 Shortcut: subjects taught by this teacher */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
            ->withPivot('school_year_id')
            ->withTimestamps();
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'user_id');
    }

    public function pushSubscriptions()
    {
        return $this->hasMany(\App\Models\PushSubscription::class);
    }
}
