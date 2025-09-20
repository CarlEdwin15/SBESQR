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
        $avatar    = $googleUser->getAvatar()
            ?? "https://ui-avatars.com/api/?name=" . urlencode("$firstName $lastName");

        // 1. Look for existing user in allowed roles
        $user = User::where('email', $email)
            ->whereIn('role', ['teacher', 'admin', 'parent'])
            ->first();

        if ($user) {
            // Update profile photo if missing
            if (!$user->profile_photo) {
                $user->update(['profile_photo' => $avatar]);
            }

            Auth::login($user);
            return redirect()->route('home');
        }

        // 2. If no user exists, only allow creating a parent account
        $user = User::create([
            'firstName'     => $firstName ?: 'Parent',
            'lastName'      => $lastName ?: 'User',
            'email'         => $email,
            'role'          => 'parent',
            'status'        => 'active',
            'password'      => bcrypt(Str::random(16)), // random password, not used for Google login
            'profile_photo' => $avatar,
        ]);

        Auth::login($user);
        return redirect()->route('home');
    }
}
