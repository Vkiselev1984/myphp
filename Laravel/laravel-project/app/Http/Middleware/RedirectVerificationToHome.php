<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectVerificationToHome
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->routeIs('verification.verify') && $response instanceof RedirectResponse) {
            $response->setTargetUrl(route('home', absolute: false).'?verified=1');
        }

        return $response;
    }
}
