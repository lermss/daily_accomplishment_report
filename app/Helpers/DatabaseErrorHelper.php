<?php

if (!function_exists('handle_db_error')) {
    /**
     * Handle database errors in controller or service layer
     *
     * @param  \Exception  $exception
     * @param  string  $context
     * @return array
     */
    function handle_db_error(Exception $exception, string $context = 'database_operation'): array
    {
        return \App\Services\DatabaseErrorService::handle($exception, $context);
    }
}

if (!function_exists('is_db_error')) {
    /**
     * Check if exception is a database error
     *
     * @param  \Exception  $exception
     * @return bool
     */
    function is_db_error(Exception $exception): bool
    {
        return $exception instanceof \Illuminate\Database\QueryException ||
               $exception instanceof \PDOException;
    }
}

if (!function_exists('is_db_connection_error')) {
    /**
     * Check if exception is a connection error
     *
     * @param  \Exception  $exception
     * @return bool
     */
    function is_db_connection_error(Exception $exception): bool
    {
        return \App\Services\DatabaseErrorService::isConnectionError($exception);
    }
}

if (!function_exists('check_db_connection')) {
    /**
     * Get database connection status
     *
     * @return array
     */
    function check_db_connection(): array
    {
        return \App\Services\DatabaseErrorService::getConnectionStatus();
    }
}
