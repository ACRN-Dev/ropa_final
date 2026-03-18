<?php

namespace App\Providers;

use App\Models\Ropa;
use App\Models\User;
use App\Observers\RopaObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register model observers — these fire automatically on
        // created / updated / deleted events for each model.
        Ropa::observe(RopaObserver::class);
        User::observe(UserObserver::class);
    }
}