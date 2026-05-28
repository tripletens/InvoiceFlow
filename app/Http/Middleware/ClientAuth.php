<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('client_id')) {
            return redirect()->route('client.login');
        }

        return $next($request);
    }
}
