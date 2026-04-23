<?php

namespace App\Http\Controllers;

use App\Services\Salla\SallaAuthService;
use App\Services\Salla\SallaOrderService;
use Illuminate\Http\Request;
use Throwable;

class SallaAuthController extends Controller
{
    public function callback(Request $request, SallaAuthService $authService)
    {
        $request->validate([
            'code' => ['required', 'string'],
            'state' => ['nullable', 'string'],
        ]);

        $token = $authService->exchangeCodeForToken($request->code);

        return response()->json([
            'message' => 'Salla connected successfully',
            'token_id' => $token->id,
        ]);
    }

    public function testCredentials(SallaAuthService $authService, SallaOrderService $orderService)
    {
        try {
            $token = $authService->getValidAccessToken();
            $ordersProbe = $orderService->list(['per_page' => 1]);

            return response()->json([
                'ok' => true,
                'message' => 'Salla credentials/token are valid',
                'access_token_preview' => substr($token, 0, 10) . '...',
                'orders_probe' => $ordersProbe,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Salla credentials/token check failed',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}