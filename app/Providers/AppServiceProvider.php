<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Ropa;
use App\Observers\RopaObserver;

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
        // ✅ Register the Ropa observer
        Ropa::observe(RopaObserver::class);
    }
}
