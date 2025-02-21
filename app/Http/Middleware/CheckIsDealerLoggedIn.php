<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsDealerLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('dealer')->check() && auth('dealer')->user()->status) {
            return redirect()->route('frontend.home');
        }

        if (auth('representative')->check()) {
            return redirect()->route('representative.dashboard');
        }

        return $next($request);
    }
}
