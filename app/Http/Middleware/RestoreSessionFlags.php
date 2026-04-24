<?php

namespace App\Http\Middleware;

use App\Services\SessionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestoreSessionFlags
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (!SessionService::hasOtp()) {
                SessionService::markOtp();
            }

            if (Auth::user()->isManager() && !SessionService::hasPassword()) {
                SessionService::markPassword();
            }
        }

        return $next($request);
    }
}