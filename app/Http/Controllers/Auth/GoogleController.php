<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $email     = $googleUser->getEmail();
        $firstName = $googleUser->user['given_name'] ?? '';
        $lastName  = $googleUser->user['family_name'] ?? '';
        // $avatar    = $googleUser->getAvatar(); // no ui-avatars fallback

        // 1. Find existing user by email and allowed roles
        $user = User::where('email', $email)
            ->whereIn('role', ['teacher', 'admin', 'parent'])
            ->first();

        if ($user) {
            // Only update profile photo if it's empty AND Google provided one
            if (!$user->profile_photo) {
                $user->update(['profile_photo']);
            }

            Auth::login($user);
            return redirect()->route('home');
        }

        // 2. If no user exists, create a parent by default
        $defaultAvatar = match ('parent') {
            'admin' => asset('assetsDashboard/img/profile_pictures/admin_default_profile.jpg'),
            'teacher' => asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg'),
            'parent' => asset('assetsDashboard/img/profile_pictures/parent_default_profile.jpg'),
            default => asset('assetsDashboard/img/profile_pictures/parent_default_profile.jpg'),
        };

        $user = User::create([
            'firstName'     => $firstName ?: 'Parent',
            'lastName'      => $lastName ?: 'User',
            'email'         => $email,
            'role'          => 'parent',
            'status'        => 'active',
            'password'      => bcrypt(Str::random(16)), // random password, not used for Google login
            'profile_photo' => $defaultAvatar,
        ]);

        Auth::login($user);
        return redirect()->route('home');
    }
}
