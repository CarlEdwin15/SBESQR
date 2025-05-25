<?php

namespace App\Models;

use App\Actions\Fortify;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'middleName',
        'extName',
        'email',
        'gender',
        'class_id',
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
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    // for formatting grade level column in the table of teacher's list
    // public function getFormattedGradeLevelAttribute()
    // {
    //     return match ($this->grade_level_assigned) {
    //         'kindergarten' => 'Kindergarten',
    //         'grade1' => 'Grade 1',
    //         'grade2' => 'Grade 2',
    //         'grade3' => 'Grade 3',
    //         'grade4' => 'Grade 4',
    //         'grade5' => 'Grade 5',
    //         'grade6' => 'Grade 6',
    //         default => ucfirst($this->grade_level_assigned)
    //     };
    // }
}
    // /**
    //  * The attributes that should be hidden for serialization.
    //  *
    //  * @var array<int, string>
    //  */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    //     'two_factor_recovery_codes',
    //     'two_factor_secret',
    // ];

    // /**
    //  * The accessors to append to the model's array form.
    //  *
    //  * @var array<int, string>
    //  */
    // protected $appends = [
    //     'profile_photo_url',
    // ];

    // /**
    //  * Get the attributes that should be cast.
    //  *
    //  * @return array<string, string>
    //  */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }
