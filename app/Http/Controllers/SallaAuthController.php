<?php

namespace App\Http\Controllers;

use App\Services\Salla\SallaAuthService;
use App\Services\Salla\SallaOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class SallaAuthController extends Controller
{
    public function companyConnect(SallaAuthService $authService)
    {
        $companyId = Auth::guard('company')->id();
        abort_unless($companyId, 403);

        $statePayload = [
            'company_id' => $companyId,
            'nonce' => uniqid('', true),
        ];

        $state = rtrim(strtr(base64_encode(json_encode($statePayload)), '+/', '-_'), '=');

        return redirect(
            $authService->getAuthorizationUrl(
                state: $state,
                scopes: ['offline_access', 'orders.read_write']
            )
        );
    }

    public function callback(Request $request, SallaAuthService $authService)
    {
        $request->validate([
            'code' => ['required', 'string'],
            'state' => ['nullable', 'string'],
        ]);
        // log the request
        Log::info('Salla callback request', $request->all());

        $companyId = null;
        if ($request->filled('state')) {
            $normalizedState = strtr($request->state, '-_', '+/');
            $paddedState = str_pad($normalizedState, strlen($normalizedState) % 4 === 0 ? strlen($normalizedState) : strlen($normalizedState) + (4 - strlen($normalizedState) % 4), '=', STR_PAD_RIGHT);
            $decodedState = json_decode(base64_decode($paddedState), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedState)) {
                Log::info('Salla decoded state', $decodedState);
                $companyId = isset($decodedState['company_id']) ? (int) $decodedState['company_id'] : null;
            }
        }

        $merchantId = $request->integer('merchant')
            ?: $request->integer('merchant_id')
            ?: $request->integer('store_id');

        $token = $authService->exchangeCodeForToken($request->code, $merchantId ?: null);
        if (!$merchantId) {
            $merchantId = $authService->detectMerchantIdFromApi($token->access_token);
        }
        Log::info('Salla token', $token->toArray());
        $token->update([
            'company_id' => $companyId,
            'merchant_id' => $merchantId ?: $token->merchant_id,
        ]);

        if ($companyId) {
            return redirect('/company')->with('success', 'Salla connected successfully');
        }

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