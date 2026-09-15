<?php

namespace App\Support;

use Kreait\Firebase\Contract\Messaging;

class FirebaseCredentials
{
    public static function path(): ?string
    {
        $candidates = [];

        $configured = config('services.firebase.credentials');
        if (is_string($configured) && trim($configured) !== '') {
            $candidates[] = trim($configured);
        }

        $candidates[] = storage_path('app/madarexpress-firebase-adminsdk-3facz-ae95777956.json');

        foreach ($candidates as $path) {
            $resolved = self::resolve($path);
            if ($resolved) {
                return $resolved;
            }
        }

        foreach (array_merge(
            glob(storage_path('app/*firebase*.json')) ?: [],
            glob(storage_path('app/*adminsdk*.json')) ?: []
        ) as $file) {
            if (is_file($file)) {
                return realpath($file) ?: $file;
            }
        }

        return null;
    }

    public static function status(): array
    {
        $path = self::path();
        $jsonProject = null;
        $validJson = false;
        $clientEmail = null;

        if ($path && is_file($path)) {
            $json = json_decode((string) file_get_contents($path), true);
            $validJson = is_array($json) && !empty($json['client_email']) && !empty($json['private_key']);
            $jsonProject = is_array($json) ? ($json['project_id'] ?? null) : null;
            $clientEmail = is_array($json) ? ($json['client_email'] ?? null) : null;
        }

        return [
            'configured_path' => config('services.firebase.credentials'),
            'resolved_path' => $path,
            'exists' => (bool) $path,
            'valid_json' => $validJson,
            'project_id' => $jsonProject ?: config('services.firebase.project_id'),
            'client_email' => $clientEmail,
            'messaging_bound' => app()->bound(Messaging::class),
        ];
    }

    protected static function resolve(string $path): ?string
    {
        $path = trim($path, " \t\n\r\0\x0B\"'");
        if ($path === '') {
            return null;
        }

        $tries = [$path];
        if (!preg_match('/^(?:[\/\\\\]|[A-Za-z]:[\/\\\\])/', $path)) {
            $tries[] = base_path($path);
            $tries[] = storage_path($path);
            $tries[] = storage_path('app/'.$path);
        }

        foreach ($tries as $try) {
            if (is_file($try)) {
                return realpath($try) ?: $try;
            }
        }

        return null;
    }
}
