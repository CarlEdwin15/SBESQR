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
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('error.not_authorized');
        }

        $email     = $googleUser->getEmail();
        $firstName = $googleUser->user['given_name'] ?? '';
        $lastName  = $googleUser->user['family_name'] ?? '';

        // 1. Find existing user by email and allowed roles
        $user = User::where('email', $email)
            ->whereIn('role', ['teacher', 'admin', 'parent'])
            ->first();

        // 2. If user is not found, redirect to 401_not_authorized
        if (!$user) {
            return redirect()->route('error.not_authorized');
        }

        // 3. Check user status before login
        if (in_array($user->status, ['inactive', 'suspended', 'banned'])) {
            switch ($user->status) {
                case 'inactive':
                    return redirect()->route('error.inactive');
                case 'suspended':
                    return redirect()->route('error.suspended');
                case 'banned':
                    return redirect()->route('error.banned');
            }
        }

        // 4. Log in authorized, active user
        Auth::login($user);

        return redirect()->route('home');
    }
}
