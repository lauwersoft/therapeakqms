<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureApproved
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && ! $request->user()->approved) {
            // Allow logout and the pending page
            if ($request->routeIs('logout') || $request->routeIs('approval.pending')) {
                return $next($request);
            }

            return redirect()->route('approval.pending');
        }

        return $next($request);
    }
}
