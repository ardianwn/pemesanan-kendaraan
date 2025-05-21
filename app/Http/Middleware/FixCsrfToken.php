<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FixCsrfToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure the session is started
        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }
        
        // Make sure the session has a CSRF token
        if (!$request->session()->has('_token')) {
            $request->session()->regenerateToken();
            Log::info('CSRF token regenerated in middleware');
        }
        
        return $next($request);
    }
}
