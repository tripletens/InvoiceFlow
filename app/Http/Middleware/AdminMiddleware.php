<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminMiddleware
 *
 * Guards admin-only routes. Returns 403 if the user is not an admin.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->is_admin) {
            abort(403, 'Access denied. Admins only.');
        }

        return $next($request);
    }
}
