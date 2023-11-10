<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        // Check if "admin_user" is in the $guards array
        // if (in_array('admin_user', $guards)) {
        //     // Redirect to the admin panel route
        //     if (Auth::guard("admin_user")->check()) {
        //         // Redirect to the admin panel route
        //         return redirect(RouteServiceProvider::ADMINPANEL);
        //     }
        // }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard == 'admin_users') {
                    return redirect(RouteServiceProvider::ADMINPANEL);
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
