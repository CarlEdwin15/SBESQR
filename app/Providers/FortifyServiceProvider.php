<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(\App\Actions\Fortify\CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(\App\Actions\Fortify\UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(\App\Actions\Fortify\UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(\App\Actions\Fortify\ResetUserPassword::class);

        // 🔑 Authentication (only validate user & password)
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return null; // email not found
            }

            // Parents are not allowed password login
            if ($user->role === 'parent') {

                session()->flash('error_redirect', "error.not_authorized");
                return null;
            }

            // Wrong password
            if (!Hash::check($request->password, $user->password)) {
                return null;
            }

            // Only teachers/admins allowed
            if (in_array($user->role, ['teacher', 'admin'])) {
                return $user;
            }

            // Any other roles blocked
            session()->flash('error_redirect', "error.not_authorized");
            return null;
        });

        // Login throttling
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
