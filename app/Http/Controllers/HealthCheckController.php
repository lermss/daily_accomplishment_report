<?php

namespace App\Http\Controllers;

use App\Services\DatabaseErrorService;
use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    /**
     * Check application health status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status()
    {
        $dbStatus = DatabaseErrorService::getConnectionStatus();

        return response()->json([
            'status' => $dbStatus['connected'] ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'checks' => [
                'database' => [
                    'connected' => $dbStatus['connected'],
                    'host' => $dbStatus['host'],
                    'port' => $dbStatus['port'],
                    'database' => $dbStatus['database'],
                    'error' => $dbStatus['error'] ?? null,
                ],
                'app' => [
                    'debug' => config('app.debug'),
                    'environment' => config('app.env'),
                ],
            ],
        ], $dbStatus['connected'] ? 200 : 503);
    }

    /**
     * Get database connection info
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function database()
    {
        $status = DatabaseErrorService::getConnectionStatus();

        return response()->json([
            'connected' => $status['connected'],
            'host' => $status['host'],
            'port' => $status['port'],
            'database' => $status['database'],
            'error' => $status['error'] ?? null,
        ], $status['connected'] ? 200 : 503);
    }

    /**
     * Attempt to reconnect to database
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reconnect()
    {
        $success = DatabaseErrorService::attemptReconnect();

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Reconnection successful' : 'Reconnection failed',
        ], $success ? 200 : 503);
    }
}
