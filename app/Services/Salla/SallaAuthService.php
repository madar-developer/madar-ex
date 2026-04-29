<?php

namespace App\Services\Salla;

use App\Models\SallaToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SallaAuthService
{
    public function getAuthorizationUrl(string $state, array $scopes = ['offline_access']): string
    {
        $query = http_build_query([
            'client_id' => config('salla.client_id'),
            'redirect_uri' => config('salla.redirect_uri'),
            'response_type' => 'code',
            'scope' => implode(' ', $scopes),
            'state' => $state,
        ]);

        return rtrim(config('salla.oauth_base_url'), '/') . '/auth?' . $query;
    }

    public function exchangeCodeForToken(string $code, ?int $merchantId = null): SallaToken
    {
        $response = Http::asForm()->post(
            rtrim(config('salla.oauth_base_url'), '/') . '/token',
            [
                'grant_type' => 'authorization_code',
                'client_id' => config('salla.client_id'),
                'client_secret' => config('salla.client_secret'),
                'redirect_uri' => config('salla.redirect_uri'),
                'code' => $code,
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException('Failed to exchange Salla authorization code: ' . $response->body());
        }

        return $this->storeTokenPayload($response->json(), $merchantId);
    }

    public function getValidAccessToken(?int $merchantId = null): string
    {
        if (config('salla.access_token')) {
            return config('salla.access_token');
        }

        $token = SallaToken::query()
            ->when($merchantId, fn ($q) => $q->where('merchant_id', $merchantId))
            ->latest('id')
            ->first();

        if (! $token) {
            throw new RuntimeException('No Salla token found.');
        }

        if ($token->access_token_expires_at && now()->lt($token->access_token_expires_at->subMinute())) {
            return $token->access_token;
        }

        return $this->refreshAccessToken($token)->access_token;
    }

    public function refreshAccessToken(SallaToken $token): SallaToken
    {
        if (! $token->refresh_token) {
            throw new RuntimeException('Salla refresh token is missing.');
        }

        $lockKey = 'salla_refresh_lock_' . ($token->merchant_id ?? $token->id);
        $lock = Cache::lock($lockKey, 15);

        try {
            $lock->block(10);

            $freshToken = SallaToken::findOrFail($token->id);

            if (
                $freshToken->access_token_expires_at &&
                now()->lt($freshToken->access_token_expires_at->copy()->subMinute())
            ) {
                return $freshToken;
            }

            $response = Http::asForm()->post(
                rtrim(config('salla.oauth_base_url'), '/') . '/token',
                [
                    'grant_type' => 'refresh_token',
                    'client_id' => config('salla.client_id'),
                    'client_secret' => config('salla.client_secret'),
                    'refresh_token' => $freshToken->refresh_token,
                ]
            );

            if ($response->failed()) {
                throw new RuntimeException('Failed to refresh Salla token: ' . $response->body());
            }

            return $this->storeTokenPayload($response->json(), $freshToken->merchant_id, $freshToken);
        } finally {
            optional($lock)->release();
        }
    }

    public function detectMerchantIdFromApi(string $accessToken): ?int
    {
        $candidateUrls = [
            rtrim(config('salla.base_url'), '/') . '/store/info',
            rtrim(config('salla.base_url'), '/') . '/store',
            rtrim(config('salla.base_url'), '/') . '/me',
        ];

        foreach ($candidateUrls as $url) {
            $response = Http::withToken($accessToken)->acceptJson()->get($url);
            if ($response->failed()) {
                continue;
            }

            $payload = $response->json();
            $merchantId = $this->extractMerchantId($payload);
            if ($merchantId !== null) {
                return $merchantId;
            }
        }

        return null;
    }

    protected function extractMerchantId($payload): ?int
    {
        if (!is_array($payload)) {
            return null;
        }

        $possiblePaths = [
            'merchant.id',
            'merchant_id',
            'store.id',
            'data.merchant.id',
            'data.merchant_id',
            'data.store.id',
            'data.id',
            'id',
        ];

        foreach ($possiblePaths as $path) {
            $value = data_get($payload, $path);
            if (is_numeric($value)) {
                return (int) $value;
            }
        }

        return null;
    }

    protected function storeTokenPayload(array $payload, ?int $merchantId = null, ?SallaToken $token = null): SallaToken
    {
        $token ??= new SallaToken();
        $resolvedMerchantId = $merchantId
            ?? (isset($payload['merchant_id']) ? (int) $payload['merchant_id'] : null)
            ?? (isset($payload['merchant']) && is_numeric($payload['merchant']) ? (int) $payload['merchant'] : null);

        $accessTokenExpiresAt = isset($payload['expires'])
            ? Carbon::createFromTimestamp((int) $payload['expires'])
            : now()->addDays(14);

        $refreshTokenExpiresAt = isset($payload['refresh_token'])
            ? now()->addMonth()
            : $token->refresh_token_expires_at;

        $token->fill([
            'merchant_id' => $resolvedMerchantId,
            'access_token' => $payload['access_token'] ?? $token->access_token,
            'refresh_token' => $payload['refresh_token'] ?? $token->refresh_token,
            'access_token_expires_at' => $accessTokenExpiresAt,
            'refresh_token_expires_at' => $refreshTokenExpiresAt,
        ]);

        $token->save();

        return $token->fresh();
    }
}