<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Announcement;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Single global view composer for all views
        View::composer('*', function ($view) {
            $notifications = collect();
            $user = Auth::user();

            if ($user) {
                $notifications = Announcement::whereHas('recipients', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                    ->orderByDesc('date_published')
                    ->take(99)
                    ->get();
            }

            $view->with('notifications', $notifications);
        });
    }
}
