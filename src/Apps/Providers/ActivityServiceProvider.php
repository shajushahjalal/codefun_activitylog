<?php

namespace CodeFun\Activitylog\App\Providers;

use Illuminate\Support\ServiceProvider;

class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'Activity');

        /**
         * Published Files
         */
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/codefun/'),
        ], "codefun_activity");
    }
}
