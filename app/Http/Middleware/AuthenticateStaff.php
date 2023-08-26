<?php

namespace App\Http\Middleware;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateStaff
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->isStaff())) {
            // OK
        } else {
            // Not authorised
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorised.', 401);
            } else {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
