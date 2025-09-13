<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Update on login or if last update is older than 1 min
            if (!$user->last_activity_at || now()->diffInMinutes($user->last_activity_at) >= 1) {
                $user->forceFill(['last_activity_at' => now()])->save();
            }
        }

        return $next($request);
    }
}
