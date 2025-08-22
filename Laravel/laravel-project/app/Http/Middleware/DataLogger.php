<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataLogger
{
    public function handle(Request $request, Closure $next)
    {
        if (!filter_var(env('LOGGING_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) {
            return $next($request);
        }

        $start = microtime(true);

        $response = $next($request);

        try {
            $duration = (microtime(true) - $start) * 1000.0; // ms

            // Ограничим размер input, чтобы не раздувать БД
            $payload = $request->all();
            $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE);
            if (strlen($encoded) > 65535) {
                $encoded = json_encode(['truncated' => true]);
            }

            DB::connection('mysql')->table('logs')->insert([
                'time' => now(),
                'duration' => $duration,
                'ip' => $request->ip() ?? '',
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'input' => $encoded,
            ]);
        } catch (\Throwable $e) {
            // Без падения приложения, можно залогировать в файл при необходимости
        }

        return $response;
    }
}
