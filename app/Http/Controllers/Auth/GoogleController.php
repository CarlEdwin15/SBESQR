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

        $email = $googleUser->getEmail();
        $firstName = $googleUser->user['given_name'] ?? '';
        $lastName = $googleUser->user['family_name'] ?? '';
        $avatar = $googleUser->getAvatar() ?? "https://ui-avatars.com/api/?name=" . urlencode("$firstName $lastName");

        // 1. Check if user is a teacher or admin in users table
        $user = User::where('email', $email)
            ->whereIn('role', ['teacher', 'admin'])
            ->first();

        if ($user) {
            Auth::login($user);
            return match ($user->role) {
                'teacher' => view('teacher.index'),
                'admin' => view('admin.index'),
            };
        }

        // 2. Check if email exists in parent_info
        $parentInfo = ParentInfo::where('parent_email', $email)->first();

        if ($parentInfo) {
            // Optional: store session or auth flag to indicate parent is logged in
            session(['parent_email' => $email]);

            return view('parent.index');
        }

        // 3. If not found anywhere → Not authorized
        return response()->view('auth.not_authorized', [], 403);
    }
}
