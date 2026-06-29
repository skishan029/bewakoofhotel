<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('admin')->check()){
            $lggedin = Auth::guard('admin')->user();
            if(in_array($lggedin->user_type, ['1','2'])){
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('admin.logout');
            }
        }
        return $next($request);
    }
}
