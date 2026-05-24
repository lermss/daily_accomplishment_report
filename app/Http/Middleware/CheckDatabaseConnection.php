<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckDatabaseConnection
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
        try {
            // Attempt a simple database query to check connection
            DB::connection()->getPdo();
        } catch (QueryException $e) {
            Log::critical('Database Connection Failed', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'connection' => config('database.default'),
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
            ]);

            // Return to database error view
            return response()->view('errors.database-error', [
                'error' => $e->getMessage(),
            ], 503);
        } catch (\PDOException $e) {
            Log::critical('PDO Connection Failed', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->view('errors.database-error', [
                'error' => $e->getMessage(),
            ], 503);
        }

        return $next($request);
    }
}
