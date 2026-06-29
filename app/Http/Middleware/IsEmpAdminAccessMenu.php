<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsEmpAdminAccessMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $menuid): Response
    {
        abort_if(empty($menuid), 401);
        $accessMenus = \App\Models\AccessMenu::where([
            ['user_id', '=', Auth::guard('admin')->id()],
            ['menu_id', '=', $menuid]
        ])->first();
        abort_if(blank($accessMenus), 401, 'menu');
        return $next($request);
    }
}
