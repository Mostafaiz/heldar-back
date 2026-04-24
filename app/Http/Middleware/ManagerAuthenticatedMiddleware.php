<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerAuthenticatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('authenticated_with_password')) {
            if ($request->method() === 'GET' && !$request->expectsJson()) {
                session(['url.intended' => $request->fullUrl()]);
            }
            return redirect()->route('manager.login');
        }
        return $next($request);
    }
}
