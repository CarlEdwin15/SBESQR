<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage: ->middleware('role:admin,teacher')
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Ensure user has a role field or relationship
        $userRole = $user->role ?? null;

        if (!$userRole || !in_array(strtolower($userRole), array_map('strtolower', $roles))) {
            abort(403, 'Unauthorized: You do not have permission to access this page.');
        }

        return $next($request);
    }
}
