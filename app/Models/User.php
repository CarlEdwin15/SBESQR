<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;
use App\Models\Student;

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
        'status',
        'sign_in_at',
        'last_sign_in_at',
        'parent_type',
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
        'sign_in_at' => 'datetime',
        'last_sign_in_at' => 'datetime',
    ];

    /** Full name accessor */
    public function getFullNameAttribute()
    {
        $middle = $this->middleName ? " {$this->middleName}" : '';
        $ext    = $this->extName ? " {$this->extName}" : '';

        return "{$this->firstName}{$middle} {$this->lastName}{$ext}";
    }

    /** Many-to-many: a parent can have multiple children */
    public function children()
    {
        return $this->belongsToMany(Student::class, 'student_parent', 'parent_id', 'student_id');
    }

    /** Classes the teacher is assigned to (via class_user pivot) */
    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_user', 'user_id', 'class_id')
            ->withPivot('role', 'school_year_id')
            ->withTimestamps();
    }

    public function advisoryClasses()
    {
        return $this->classes()->wherePivot('role', 'adviser');
    }

    public function subjectClasses()
    {
        return $this->classes()->wherePivot('role', 'subject_teacher');
    }

    public function schoolYears()
    {
        return $this->belongsToMany(SchoolYear::class, 'class_user', 'user_id', 'school_year_id')
            ->withPivot('class_id', 'role')
            ->withTimestamps();
    }

    public function classSubjects()
    {
        return $this->hasManyThrough(
            ClassSubject::class,
            Classes::class,
            'id',       // Classes PK
            'class_id', // ClassSubject FK
            'id',       // User PK
            'id'        // Classes PK
        );
    }

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

    protected $appends = ['is_online', 'last_seen'];

    public function getIsOnlineAttribute()
    {
        if (!$this->id) {
            return false;
        }

        $session = DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->first();

        return $session !== null;
    }

    public function getLastSeenAttribute()
    {
        if (!$this->last_sign_in_at) {
            return 'Not signed in yet';
        }

        return Carbon::parse($this->last_sign_in_at)->diffForHumans();
    }

    public function getLastLoginAttribute()
    {
        return $this->sign_in_at
            ? $this->sign_in_at->diffForHumans()
            : 'Never';
    }

    // Mutator for saving phone number
    public function setPhoneAttribute($value)
    {
        if (!$value) {
            $this->attributes['phone'] = null;
            return;
        }

        // Remove spaces, dashes, and parentheses just in case
        $phone = preg_replace('/[^0-9]/', '', $value);

        // If it starts with 09 → convert to +639
        if (preg_match('/^09\d{9}$/', $phone)) {
            $phone = '+63' . substr($phone, 1);
        }

        // If it already starts with +639 → leave as is
        if (preg_match('/^\+639\d{9}$/', $phone)) {
            $this->attributes['phone'] = $phone;
            return;
        }

        // Fallback: just save cleaned number
        $this->attributes['phone'] = $phone;
    }

    // Accessor for displaying phone number
    public function getPhoneAttribute($value)
    {
        if (!$value) return null;

        // Convert +639XXXXXXXXX → 09XXXXXXXXX
        if (str_starts_with($value, '+639')) {
            return '0' . substr($value, 3);
        }

        return $value;
    }
}
