<?php

namespace App\Http\Middleware;

use App\Models\LogAplikasi;
use App\Services\LogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuditTrailMiddleware
{
    /**
     * List of paths that should not be logged
     */
    protected $excludedPaths = [
        'storage',
        'build',
        'favicon.ico',
        '_debugbar',
        'api/user',
        'sanctum/csrf-cookie'
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Store the starting time to measure execution time
        $startTime = microtime(true);
        
        // Store initial data
        $method = $request->method();
        $url = $request->fullUrl();
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $path = $request->path();
        
        // Process the request
        $response = $next($request);
        
        // Skip logging for certain paths like assets, images, etc.
        if ($this->shouldSkipLogging($path)) {
            return $response;
        }
        
        // Calculate execution time
        $executionTime = microtime(true) - $startTime;
        
        // Get authenticated user if available
        $userId = Auth::id();
        
        // Create log entry
        if ($userId) {
            LogAplikasi::create([
                'user_id' => $userId,
                'aktivitas' => $method,
                'tabel' => 'system',
                'id_data' => null,
                'deskripsi' => "Access to {$url} - Time: " . round($executionTime, 4) . 's, Status: ' . $response->getStatusCode(),
            ]);
        }
        
        return $response;
    }
    
    /**
     * Determine if logging should be skipped for the given path
     *
     * @param string $path
     * @return bool
     */
    protected function shouldSkipLogging(string $path): bool
    {
        // Skip assets, images, and other static resources
        foreach ($this->excludedPaths as $excludedPath) {
            if (str_starts_with($path, $excludedPath)) {
                return true;
            }
        }
        
        // Skip logging for assets
        $skipPatterns = [
            '.js', '.css', '.jpg', '.jpeg', '.png', '.gif', '.ico', '.svg',
            '/js/', '/css/', '/images/', '/fonts/', '/storage/'
        ];
        
        foreach ($skipPatterns as $pattern) {
            if (str_contains($path, $pattern)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Filter sensitive data from request
     *
     * @param array $data
     * @return array
     */
    protected function filterSensitiveData(array $data): array
    {
        // Remove sensitive data
        $sensitiveFields = ['password', 'password_confirmation', '_token', 'csrf_token'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***HIDDEN***';
            }
        }
        
        return $data;
    }
}
