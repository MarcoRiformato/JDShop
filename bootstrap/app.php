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
            'editor.restrictions' => \App\Http\Middleware\CheckEditorRestrictions::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Check if request expects JSON (API routes)
        $isApiRequest = function (\Illuminate\Http\Request $request): bool {
            return $request->expectsJson() || 
                   $request->is('admin/*') || 
                   $request->is('api/*') ||
                   $request->wantsJson();
        };

        // Handle 413 Payload Too Large errors specifically for file uploads
        $exceptions->render(function (\Throwable $exception, \Illuminate\Http\Request $request) use ($isApiRequest) {
            // Handle file size errors
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && 
                $exception->getStatusCode() === 413 && 
                $request->is('admin/products/*/images')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Il file è troppo grande. Dimensione massima consentita: 10MB.',
                    'error' => 'File too large'
                ], 413);
            }

            // Handle validation errors - return JSON for API routes
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                if ($isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $exception->errors(),
                    ], 422);
                }
                // Let Laravel handle Inertia validation errors normally
                return null;
            }

            // Handle database connection errors
            if ($exception instanceof \Illuminate\Database\QueryException || 
                $exception instanceof \PDOException) {
                \Illuminate\Support\Facades\Log::error('Database error', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'url' => $request->fullUrl(),
                ]);

                if ($isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => config('app.debug') 
                            ? 'Database error: ' . $exception->getMessage()
                            : 'Errore del database. Riprova più tardi.',
                        'error' => 'Database error'
                    ], 500);
                }

                return null; // Let Laravel show the default error page
            }

            // Handle authentication errors
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                if ($isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Non autenticato. Effettua il login.',
                        'error' => 'Unauthenticated'
                    ], 401);
                }
                return null; // Let Laravel redirect to login
            }

            // Handle authorization errors (403)
            if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException ||
                ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && 
                 $exception->getStatusCode() === 403)) {
                \Illuminate\Support\Facades\Log::warning('Authorization failed', [
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'url' => $request->fullUrl(),
                ]);

                if ($isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Non hai il permesso di accedere a questa risorsa.',
                        'error' => 'Forbidden'
                    ], 403);
                }
                return null;
            }

            // Handle 404 Not Found errors
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                if ($isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Risorsa non trovata.',
                        'error' => 'Not Found'
                    ], 404);
                }
                return null; // Let Laravel show default 404 page
            }

            // Handle Method Not Allowed (405)
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                if ($isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Metodo HTTP non consentito per questa route.',
                        'error' => 'Method Not Allowed'
                    ], 405);
                }
                return null;
            }

            // Handle CSRF token mismatches
            if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
                \Illuminate\Support\Facades\Log::warning('CSRF token mismatch', [
                    'url' => $request->fullUrl(),
                ]);

                if ($isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token CSRF non valido. Ricarica la pagina.',
                        'error' => 'Token Mismatch'
                    ], 419);
                }
                return null;
            }

            // Handle general exceptions for API routes
            if ($isApiRequest($request) && !($exception instanceof \Illuminate\Validation\ValidationException)) {
                \Illuminate\Support\Facades\Log::error('Unhandled exception in API request', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => config('app.debug')
                        ? 'Errore: ' . $exception->getMessage()
                        : 'Si è verificato un errore imprevisto. Riprova più tardi.',
                    'error' => config('app.debug') ? get_class($exception) : 'Internal Server Error',
                    'file' => config('app.debug') ? $exception->getFile() : null,
                    'line' => config('app.debug') ? $exception->getLine() : null,
                ], 500);
            }

            return null; // Let Laravel handle other exceptions normally
        });

        // Log all exceptions for debugging
        $exceptions->report(function (\Throwable $exception) {
            if (config('app.debug')) {
                \Illuminate\Support\Facades\Log::debug('Exception logged', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'class' => get_class($exception),
                ]);
            }
        });
    })->create();
