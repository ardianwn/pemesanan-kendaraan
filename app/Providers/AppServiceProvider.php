<?php

namespace App\Providers;

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
        // Ensure CSRF token is always available for session
        if ($this->app->bound('session')) {
            $session = $this->app->make('session');
            if (!$session->has('_token')) {
                $session->put('_token', csrf_token());
            }
        }
    }
}
