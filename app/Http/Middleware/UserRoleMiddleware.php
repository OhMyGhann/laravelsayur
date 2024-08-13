<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('app/login') || $request->is('app/register')) {
            return $next($request);
        }

        if ($request->is('app') || $request->is('app/*')) {
            return $next($request);
        }

        if (Auth::check()) {
            if (Auth::user()->is_admin) {
                return redirect('/admin');
            } else {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
