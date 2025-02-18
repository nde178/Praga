<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    public function handle($request, Closure $next)
    {
        // Логируем информацию о запросе
        Log::info('Запрос:', [
            'method' => $request->method(),
            'url' => $request->url(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        return $next($request);
    }
}

