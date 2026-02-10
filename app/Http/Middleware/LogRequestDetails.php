<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogRequestDetails
{
    /**
     * Handle an incoming request and log admin request timing and queries.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! str_starts_with($request->path(), 'admin')) {
            return $next($request);
        }

        DB::connection()->enableQueryLog();
        $start = microtime(true);

        $response = $next($request);

        $durationMs = round((microtime(true) - $start) * 1000, 2);
        $queries = DB::getQueryLog();

        Log::info('Admin request detail', [
            'path' => $request->path(),
            'method' => $request->method(),
            'duration_ms' => $durationMs,
            'queries_count' => count($queries),
            'queries' => $queries,
        ]);

        return $response;
    }
}
