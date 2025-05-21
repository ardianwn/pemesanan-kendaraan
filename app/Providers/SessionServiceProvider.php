<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SessionServiceProvider extends ServiceProvider
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
        // Set secure cookies in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            
            // Force secure cookies
            config(['session.secure' => true]);
            config(['session.same_site' => 'lax']);
        }
        
        // Make sure session cookie is properly configured
        Cookie::setDefaultPathAndDomain(
            config('session.path'), 
            config('session.domain'), 
            config('session.secure') ?? false, 
            config('session.same_site') ?? 'lax'
        );
    }
}
