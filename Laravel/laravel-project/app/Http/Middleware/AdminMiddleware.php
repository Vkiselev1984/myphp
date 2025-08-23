<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !((bool) (auth()->user()->is_admin ?? false))) {
            abort(403);
        }
        return $next($request);
    }
}
