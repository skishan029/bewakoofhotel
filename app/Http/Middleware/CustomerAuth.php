<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('customer')->check()) {
            $lggedin = Auth::guard('customer')->user();
            if ($lggedin->is_active == true && $lggedin->is_verified == true) {
                return $next($request);
            } else {
                return redirect()->route('customer.logout');
            }
        }
        return redirect()->route('customer.login');
    }
}
