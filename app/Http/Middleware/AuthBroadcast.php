<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthBroadcast
{
    public function handle($request, Closure $next)
    {
        // Teachers & Admins: Logged in via Laravel Auth
        if (Auth::check()) {
            return $next($request);
        }

        // Parents: Session-based Google login
        if (
            session()->has('email') &&
            User::where('email', session('email'))->exists()
        ) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}
