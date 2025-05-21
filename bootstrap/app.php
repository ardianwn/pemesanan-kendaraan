<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ApproverMiddleware;
use App\Http\Middleware\AuditTrailMiddleware;
use App\Http\Middleware\FixCsrfToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            AuditTrailMiddleware::class,
            FixCsrfToken::class,
        ]);
        
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'approver' => ApproverMiddleware::class,
            'audit' => AuditTrailMiddleware::class,
            'fix-csrf' => FixCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
