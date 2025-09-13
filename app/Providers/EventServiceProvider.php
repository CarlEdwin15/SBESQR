<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Listeners\UpdateLastActivity;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    // protected $listen = [
    //     \Illuminate\Auth\Events\Login::class => [
    //         \App\Listeners\UpdateUserLastSignIn::class,
    //     ],
    //     \Illuminate\Auth\Events\Logout::class => [
    //         \App\Listeners\UpdateUserLastSeen::class,
    //     ],
    // ];

    public function boot(): void
    {
        parent::boot();
    }
}
