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

        $email = $googleUser->getEmail();
        $firstName = $googleUser->user['given_name'] ?? '';
        $lastName = $googleUser->user['family_name'] ?? '';
        $avatar = $googleUser->getAvatar() ?? "https://ui-avatars.com/api/?name=" . urlencode("$firstName $lastName");

        // Check if the email is already registered
        $user = User::where('email', $email)->first();

        if ($user) {
            // If user exists, log in and redirect by role
            Auth::login($user);

            return match ($user->role) {
                'teacher' => view('teacher.index'),
                'admin' => view('admin.index'),
                default => view('parent.index'),
            };
        } else {
            // Not registered → create a new parent account
            $newUser = User::create([
                'email' => $email,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'role' => 'parent',
                'profile_photo' => $avatar,
                'password' => bcrypt(Str::random(16)), // random password
            ]);

            Auth::login($newUser);
            return view('parent.index');
        }
    }
}
