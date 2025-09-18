<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $status = Auth::user()->status;

            switch ($status) {
                case 'inactive':
                    Auth::logout();
                    return redirect()->route('error.inactive');

                case 'suspended':
                    Auth::logout();
                    return redirect()->route('error.suspended');

                case 'banned':
                    Auth::logout();
                    return redirect()->route('error.banned');
            }
        }

        return $next($request);
    }
}
