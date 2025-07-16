<?php

namespace App\Providers;

use Illuminate\Auth\Events\Authenticated;
use App\Listeners\ExecuteRecurringAfterLogin;

class EventServiceProvider extends ServiceProvider
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
        protected $listen = [
            Authenticated::class => [
                ExecuteRecurringAfterLogin::class,
            ],
        ];

    }
}

