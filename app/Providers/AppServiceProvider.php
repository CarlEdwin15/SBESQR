<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Announcement;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Make $notifications available in all views that include layouts/main
        View::composer('layouts.main', function ($view) {
            $notifications = Announcement::orderBy('date_published', 'desc')
                ->take(99)
                ->get();

            $view->with('notifications', $notifications);
        });

        // OR if you only want it inside navbar partial
        View::composer('partials.navbar', function ($view) {
            $notifications = Announcement::orderBy('date_published', 'desc')
                ->take(99)
                ->get();

            $view->with('notifications', $notifications);
        });
    }
}
