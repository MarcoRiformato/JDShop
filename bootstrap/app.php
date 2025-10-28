<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
        
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 413 Payload Too Large errors specifically for file uploads
        $exceptions->respond(function (\Illuminate\Http\Request $request, \Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            if ($exception->getStatusCode() === 413 && $request->is('admin/products/*/images')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Il file è troppo grande. Dimensione massima consentita: 10MB.',
                    'error' => 'File too large'
                ], 413);
            }
            return null;
        });
    })->create();
