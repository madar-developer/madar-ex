<?php

namespace App\Http\Middleware;

use Closure;
        use Illuminate\Support\Facades\Log;

class ApiLocaleMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Log::info('Incoming Request', [
        //     'method' => $request->method(),
        //     'url'    => $request->fullUrl(),
        //     'data'   => $request->all()
        // ]);
        if(Request()->header('lang')){
            \App::setLocale(Request()->header('lang'));
            config(['app.locale' => Request()->header('lang')]);
        }
        return $next($request);
    }
}
