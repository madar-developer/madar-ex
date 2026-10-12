<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Restricts WhatsApp send endpoints to authenticated, active companies.
 */
class EnsureWhatsAppSender
{
    public function handle(Request $request, Closure $next)
    {
        $company = auth('api-company')->user();

        if (! $company || ! $company->active) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => ['unauthorized'],
                'message' => 'Unauthorized',
                'code' => getMsgCode('authFailed'),
            ], 401);
        }

        return $next($request);
    }
}
