<?php

namespace App\Services;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use PDOException;

class DatabaseErrorService
{
    /**
     * Handle and log database errors gracefully
     *
     * @param  \Exception  $exception
     * @param  string  $context
     * @return array
     */
    public static function handle(Exception $exception, string $context = 'database_operation'): array
    {
        $isDbError = $exception instanceof QueryException || 
                     $exception instanceof PDOException;

        if ($isDbError) {
            // Log the full error for debugging
            Log::error("Database Error in {$context}", [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'code' => $exception->getCode(),
                'connection' => config('database.default'),
                'sql' => $exception instanceof QueryException ? 
                    ($exception->getSql() ?? 'N/A') : 'N/A',
                'bindings' => $exception instanceof QueryException ? 
                    ($exception->getBindings() ?? []) : [],
            ]);

            // Return user-friendly message
            return [
                'success' => false,
                'message' => 'Unable to process your request. Please try again later.',
                'error_code' => 'DB_ERROR',
                'is_connection_error' => self::isConnectionError($exception),
            ];
        }

        return [
            'success' => false,
            'message' => 'An unexpected error occurred.',
            'error_code' => 'UNKNOWN_ERROR',
        ];
    }

    /**
     * Determine if the error is a connection error
     *
     * @param  \Exception  $exception
     * @return bool
     */
    public static function isConnectionError(Exception $exception): bool
    {
        if ($exception instanceof QueryException) {
            $message = $exception->getMessage();
            return str_contains($message, '2002') ||
                   str_contains($message, '2003') ||
                   str_contains($message, 'connection') ||
                   str_contains($message, 'refused') ||
                   str_contains($message, 'SQLSTATE[HY000]');
        }

        if ($exception instanceof PDOException) {
            return str_contains($exception->getMessage(), 'connection');
        }

        return false;
    }

    /**
     * Attempt to reconnect to database
     *
     * @return bool
     */
    public static function attemptReconnect(): bool
    {
        try {
            $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
            Log::info('Database reconnection successful');
            return true;
        } catch (Exception $e) {
            Log::error('Database reconnection failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get database connection status
     *
     * @return array
     */
    public static function getConnectionStatus(): array
    {
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            
            return [
                'connected' => true,
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => config('database.connections.mysql.database'),
            ];
        } catch (Exception $e) {
            return [
                'connected' => false,
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => config('database.connections.mysql.database'),
                'error' => $e->getMessage(),
            ];
        }
    }
}
