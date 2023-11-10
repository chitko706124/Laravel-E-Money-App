<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // manualCodexxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

        // if (is_null(session('admin_user'))) {
        //     return redirect()->route('admin.login');
        // }
        // $admin = AdminUser::where('email', session('admin_user'))->get()->random()->first();
        // if (is_null($admin)) {
        //     return redirect()->route("admin.login");
        // }

        // manualCodexxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

        $adminEmail = session('admin_user');

        if (is_null($adminEmail) || !AdminUser::where('email', $adminEmail)->exists()) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
