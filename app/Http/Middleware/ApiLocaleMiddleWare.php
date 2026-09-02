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
        $locale = $request->header('app-lang') ?: $request->header('lang');
        if ($locale) {
            $locale = strtolower(substr((string) $locale, 0, 2));
            if (in_array($locale, ['ar', 'en'], true)) {
                \App::setLocale($locale);
                config(['app.locale' => $locale]);
            }
        }
        return $next($request);
    }
}
