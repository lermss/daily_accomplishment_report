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
        // Add database connection check as early as possible
        $middleware->prepend(\App\Http\Middleware\CheckDatabaseConnection::class);
        
        $middleware->append(\App\Http\Middleware\SetSecurityHeaders::class);

        $middleware->alias([
            'admin.session' => \App\Http\Middleware\EnsureAdminSession::class,
            'admin.register' => \App\Http\Middleware\EnsureAdminRegistrationAllowed::class,
            'role.session' => \App\Http\Middleware\EnsureRoleSession::class,
            'staff.session' => \App\Http\Middleware\EnsureStaffSession::class,
            '2fa.pending' => \App\Http\Middleware\EnsurePendingTwoFactorChallenge::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle database connection errors
        $exceptions->render(function (Throwable $e) {
            // Catch database-related exceptions
            if ($e instanceof \Illuminate\Database\QueryException || 
                $e instanceof \PDOException || 
                $e instanceof \Illuminate\Database\LostConnectionException) {
                
                // Log the real error for debugging
                \Illuminate\Support\Facades\Log::error('Database Connection Error', [
                    'message' => $e->getMessage(),
                    'exception' => get_class($e),
                    'trace' => $e->getTraceAsString(),
                    'connection' => config('database.default'),
                    'host' => config('database.connections.mysql.host'),
                    'port' => config('database.connections.mysql.port'),
                    'database' => config('database.connections.mysql.database'),
                ]);

                // Return user-friendly error response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'Unable to connect to the server. Please try again later.',
                        'error' => 'database_connection_error',
                    ], 503);
                }

                // For web requests, return the custom error view
                return response()->view('errors.database-error', [
                    'error' => 'Database Connection Error',
                ], 503);
            }
        });
    })->create();
