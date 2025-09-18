<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ParentInfo;

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

        // Teachers/Admins
        $user = User::where('email', $email)
            ->whereIn('role', ['teacher', 'admin'])
            ->first();

        if ($user) {
            Auth::login($user);
            return redirect()->route('home');
        }

        // Parents (lookup in ParentInfo)
        $parentInfo = ParentInfo::where('parent_email', $email)->first();

        if ($parentInfo) {
            // Prefer mother's details if available
            if (!empty($parentInfo->mother_fName) && !empty($parentInfo->mother_lName)) {
                $firstName  = $parentInfo->mother_fName;
                $middleName = $parentInfo->mother_mName ?? null;
                $lastName   = $parentInfo->mother_lName;
                $phone      = $parentInfo->mother_phone ?? null;
            } else {
                $firstName  = $parentInfo->father_fName ?? '';
                $middleName = $parentInfo->father_mName ?? null;
                $lastName   = $parentInfo->father_lName ?? '';
                $phone      = $parentInfo->father_phone ?? null;
            }

            if (!$phone) {
                $phone = $parentInfo->emergcont_phone ?? null;
            }

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'firstName'     => $firstName,
                    'middleName'    => $middleName,
                    'lastName'      => $lastName,
                    'phone'         => $phone,
                    'role'          => 'parent',
                    'status'        => 'active',
                    'password'      => bcrypt(Str::random(16)),
                    'profile_photo' => $avatar,
                ]
            );

            Auth::login($user);
            return redirect()->route('home');
        }

        // Not authorized
        return response()->view('errors.401_not_authorized', [], 403);
    }
}
