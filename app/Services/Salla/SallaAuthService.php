<?php

namespace App\Services\Salla;

use App\Models\Company;
use App\Models\SallaToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

    public function fetchUserInfo(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get(rtrim(config('salla.oauth_base_url'), '/') . '/user/info');

        if ($response->failed()) {
            throw new RuntimeException('Failed to fetch Salla user info: ' . $response->body());
        }

        $payload = $response->json();
        if (! data_get($payload, 'success')) {
            throw new RuntimeException('Salla user info request was not successful: ' . $response->body());
        }

        return data_get($payload, 'data', []);
    }

    public function handleStoreAuthorize(array $payload): SallaToken
    {
        $tokenData = data_get($payload, 'data', []);
        $accessToken = data_get($tokenData, 'access_token');

        if (empty($accessToken)) {
            throw new RuntimeException('Salla authorize webhook missing access_token.');
        }

        $userInfo = $this->fetchUserInfo($accessToken);
        $email = trim((string) data_get($userInfo, 'email'));

        if ($email === '') {
            throw new RuntimeException('Salla user info missing email.');
        }

        $merchantId = (int) (
            data_get($payload, 'merchant')
            ?? data_get($userInfo, 'merchant.id')
            ?? 0
        );

        $company = Company::where('email', $email)->first();

        if (! $company) {
            $merchant = data_get($userInfo, 'merchant', []);
            $phone = $this->resolveCompanyPhone(data_get($userInfo, 'mobile'));

            $company = Company::create([
                'name' => data_get($merchant, 'name') ?: data_get($userInfo, 'name') ?: 'Salla Store',
                'email' => $email,
                'phone' => $phone,
                'password' => bcrypt(Str::random(32)),
                'commercial_record' => data_get($merchant, 'commercial_number') ?: ('salla-' . ($merchantId ?: uniqid())),
                'adress_details' => data_get($merchant, 'domain'),
                'active' => 1,
            ]);

            Log::info('Salla store authorize: created company', [
                'company_id' => $company->id,
                'merchant_id' => $merchantId,
                'email' => $email,
            ]);
        }

        $existingToken = $merchantId
            ? SallaToken::where('merchant_id', $merchantId)->latest('id')->first()
            : null;

        $token = $this->storeTokenPayload(
            array_merge($tokenData, ['merchant_id' => $merchantId ?: null]),
            $merchantId ?: null,
            $existingToken
        );

        $token->update([
            'company_id' => $company->id,
            'merchant_id' => $merchantId ?: $token->merchant_id,
        ]);

        Log::info('Salla store authorize: token saved', [
            'token_id' => $token->id,
            'company_id' => $company->id,
            'merchant_id' => $token->merchant_id,
        ]);

        return $token->fresh();
    }

    protected function resolveCompanyPhone(?string $mobile): ?string
    {
        $phone = trim((string) $mobile);
        if ($phone === '') {
            return null;
        }

        if (Company::where('phone', $phone)->exists()) {
            return null;
        }

        return $phone;
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