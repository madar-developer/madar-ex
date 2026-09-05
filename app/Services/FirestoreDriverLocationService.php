<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Reads live driver location from Firestore collection "drivers"
 * the same way the admin order show page does (locations[0].lat / long).
 */
class FirestoreDriverLocationService
{
    protected string $projectId;
    protected ?string $credentialsPath;

    public function __construct()
    {
        $this->projectId = (string) (config('services.firebase.project_id') ?: 'madarexpress');
        $this->credentialsPath = config('services.firebase.credentials');
    }

    /**
     * @return array{lat: float, lng: float, timestamp: ?int, updated_at: ?string, driver_id: int, driver_name: ?string, source: string}|null
     */
    public function getDriverLocation(int $driverId): ?array
    {
        try {
            $doc = $this->findDriverDocument($driverId);
            if (!$doc) {
                return null;
            }

            $locations = $doc['locations'] ?? null;
            if (!is_array($locations) || empty($locations[0])) {
                return null;
            }

            $point = $locations[0];
            $lat = $point['lat'] ?? null;
            $lng = $point['long'] ?? $point['lng'] ?? null;

            if ($lat === null || $lng === null) {
                return null;
            }

            $timestamp = isset($point['timestamp']) ? (int) $point['timestamp'] : null;
            $updatedAt = null;
            if ($timestamp) {
                $updatedAt = date('Y-m-d H:i:s', (int) floor($timestamp / 1000));
            }

            return [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
                'timestamp' => $timestamp,
                'updated_at' => $updatedAt,
                'driver_id' => $driverId,
                'driver_name' => isset($doc['name']) ? (string) $doc['name'] : null,
                'source' => 'firestore',
            ];
        } catch (\Throwable $e) {
            Log::error('FirestoreDriverLocationService failed', [
                'driver_id' => $driverId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    protected function findDriverDocument(int $driverId): ?array
    {
        // Prefer query by field "id" (matches admin JS: data.id == driverId)
        $byField = $this->runQueryByIdField($driverId);
        if ($byField) {
            return $byField;
        }

        // Fallback: document id equals driver id string
        return $this->getDocumentById((string) $driverId);
    }

    protected function runQueryByIdField(int $driverId): ?array
    {
        $token = $this->getAccessToken();
        $url = sprintf(
            'https://firestore.googleapis.com/v1/projects/%s/databases/(default)/documents:runQuery',
            $this->projectId
        );

        // Try integer first (Firestore console shows id as number)
        foreach ([['integerValue' => (string) $driverId], ['stringValue' => (string) $driverId]] as $value) {
            $response = Http::withToken($token)
                ->timeout(15)
                ->post($url, [
                    'structuredQuery' => [
                        'from' => [['collectionId' => 'drivers']],
                        'where' => [
                            'fieldFilter' => [
                                'field' => ['fieldPath' => 'id'],
                                'op' => 'EQUAL',
                                'value' => $value,
                            ],
                        ],
                        'limit' => 1,
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('Firestore runQuery failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                continue;
            }

            foreach ($response->json() ?? [] as $row) {
                if (!empty($row['document']['fields'])) {
                    return $this->decodeFields($row['document']['fields']);
                }
            }
        }

        return null;
    }

    protected function getDocumentById(string $documentId): ?array
    {
        $token = $this->getAccessToken();
        $url = sprintf(
            'https://firestore.googleapis.com/v1/projects/%s/databases/(default)/documents/drivers/%s',
            $this->projectId,
            rawurlencode($documentId)
        );

        $response = Http::withToken($token)->timeout(15)->get($url);
        if ($response->status() === 404) {
            return null;
        }
        if (!$response->successful()) {
            Log::warning('Firestore getDocument failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        $fields = $response->json('fields');
        if (!$fields) {
            return null;
        }

        return $this->decodeFields($fields);
    }

    protected function getAccessToken(): string
    {
        return Cache::remember('firebase_firestore_access_token', 50 * 60, function () {
            $sa = $this->loadServiceAccount();
            $now = time();
            $jwt = $this->makeJwt($sa, $now);

            $response = Http::asForm()
                ->timeout(15)
                ->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

            if (!$response->successful() || !$response->json('access_token')) {
                throw new \RuntimeException(
                    'Unable to obtain Google access token: '.$response->body()
                );
            }

            return (string) $response->json('access_token');
        });
    }

    protected function loadServiceAccount(): array
    {
        $path = $this->credentialsPath;
        if (!$path || !is_file($path)) {
            throw new \RuntimeException('Firebase credentials file not found: '.($path ?: '(empty)'));
        }

        $json = json_decode((string) file_get_contents($path), true);
        if (!is_array($json) || empty($json['client_email']) || empty($json['private_key'])) {
            throw new \RuntimeException('Invalid Firebase service account JSON');
        }

        if (!empty($json['project_id'])) {
            $this->projectId = (string) $json['project_id'];
        }

        return $json;
    }

    protected function makeJwt(array $sa, int $now): string
    {
        $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = $this->base64UrlEncode(json_encode([
            'iss' => $sa['client_email'],
            'sub' => $sa['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/datastore https://www.googleapis.com/auth/cloud-platform',
        ]));

        $unsigned = $header.'.'.$payload;
        $privateKey = openssl_pkey_get_private($sa['private_key']);
        if ($privateKey === false) {
            throw new \RuntimeException('Invalid Firebase private key');
        }

        $signature = '';
        $ok = openssl_sign($unsigned, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (!$ok) {
            throw new \RuntimeException('Failed to sign Google JWT');
        }

        return $unsigned.'.'.$this->base64UrlEncode($signature);
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Convert Firestore REST field map to plain PHP array.
     */
    protected function decodeFields(array $fields): array
    {
        $out = [];
        foreach ($fields as $key => $value) {
            $out[$key] = $this->decodeValue($value);
        }

        return $out;
    }

    protected function decodeValue(array $value)
    {
        if (array_key_exists('stringValue', $value)) {
            return $value['stringValue'];
        }
        if (array_key_exists('integerValue', $value)) {
            return (int) $value['integerValue'];
        }
        if (array_key_exists('doubleValue', $value)) {
            return (float) $value['doubleValue'];
        }
        if (array_key_exists('booleanValue', $value)) {
            return (bool) $value['booleanValue'];
        }
        if (array_key_exists('nullValue', $value)) {
            return null;
        }
        if (array_key_exists('timestampValue', $value)) {
            return $value['timestampValue'];
        }
        if (array_key_exists('mapValue', $value)) {
            return $this->decodeFields($value['mapValue']['fields'] ?? []);
        }
        if (array_key_exists('arrayValue', $value)) {
            $items = [];
            foreach ($value['arrayValue']['values'] ?? [] as $item) {
                $items[] = $this->decodeValue($item);
            }

            return $items;
        }

        return null;
    }
}
