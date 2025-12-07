<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // if ( Auth::guard('admin')->check() && Auth::guard('admin')->user()->role != 'admin' && Auth::guard('admin')->user()->role != 'branch' && (!Auth::guard('admin')->user()->UserRole()->first() || Auth::guard('admin')->user()->UserRole->Role->Permission()->where('permission', $role)->count() == 0 ) ) {
            
        //     return redirect('/dashboard')->with('warning', 'ليس لديك صلاحية الدخول الي هذا القسم.');
        // }
        // if( Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 'branch' && in_array($role , ['setting_show', 'admin_show', 'admin_add', 'admin_edit', 'contact_us_show']) )
        // {
        //     return redirect('/dashboard')->with('warning', 'ليس لديك صلاحية الدخول الي هذا القسم.');
        // }
        return $next($request);
    }
}
