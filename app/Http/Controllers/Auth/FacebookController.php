<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->user();

        $email = $facebookUser->getEmail();
        $firstName = $facebookUser->user['given_name'] ?? '';
        $lastName = $facebookUser->user['family_name'] ?? '';
        $fullName = trim("$firstName $lastName");

        // ✅ Fallback to initials avatar if no Google avatar is returned
        $avatar = $facebookUser->getAvatar() ?? "https://ui-avatars.com/api/?name=" . urlencode($fullName);

        // Create or update user
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'role' => 'parent',
                'profile_photo' => $avatar,
                'password' => bcrypt(Str::random(16)),
            ]
        );

        Auth::login($user);

        return view('parent.index');
    }
}
