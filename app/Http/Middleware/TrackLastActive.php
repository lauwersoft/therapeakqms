<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrackLastActive
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && (!$request->user()->last_active_at || $request->user()->last_active_at->lt(now()->subMinutes(5)))) {
            $request->user()->forceFill(['last_active_at' => now()])->saveQuietly();
        }

        return $next($request);
    }
}
