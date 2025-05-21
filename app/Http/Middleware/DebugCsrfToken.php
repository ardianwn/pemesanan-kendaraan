<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only do this for POST requests
        if ($request->isMethod('post')) {
            // Log CSRF token information for debugging
            Log::info('CSRF Debug Info', [
                'has_csrf_token' => $request->hasHeader('X-CSRF-TOKEN'),
                'csrf_token_header' => $request->header('X-CSRF-TOKEN'),
                'has_csrf_field' => $request->has('_token'),
                'csrf_field_value' => $request->input('_token'),
                'session_csrf_token' => $request->session()->token(),
                'url' => $request->fullUrl(),
                'session_active' => $request->session()->isStarted(),
            ]);
        }

        return $next($request);
    }
}
