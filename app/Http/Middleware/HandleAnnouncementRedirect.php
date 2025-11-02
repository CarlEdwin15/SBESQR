<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleAnnouncementRedirect
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // After successful login, check if there's an announcement to redirect to
        if (Auth::check() && session()->has('login_redirect_announcement')) {
            $announcementId = session('login_redirect_announcement');
            session()->forget('login_redirect_announcement');

            // Store in notification session for home page to pick up
            session(['notification_announcement_id' => $announcementId]);
        }

        return $response;
    }
}
