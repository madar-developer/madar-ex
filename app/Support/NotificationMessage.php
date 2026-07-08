<?php

namespace App\Support;

use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Cache;

class NotificationMessage
{
    public static function render(string $key, array $replace = []): string
    {
        $template = self::findTemplate($key);
        $body = $template?->body ?? self::defaultBody($key);

        foreach ($replace as $placeholder => $value) {
            $body = str_replace('{' . $placeholder . '}', (string) $value, $body);
        }

        return $body;
    }

    public static function renderTitle(string $key, array $replace = []): ?string
    {
        $template = self::findTemplate($key);
        $title = $template?->title;

        if (!$title) {
            return null;
        }

        foreach ($replace as $placeholder => $value) {
            $title = str_replace('{' . $placeholder . '}', (string) $value, $title);
        }

        return $title;
    }

    public static function syncDefaults(): int
    {
        $count = 0;

        foreach (NotificationTemplateDefinitions::all() as $definition) {
            NotificationTemplate::updateOrCreate(
                ['key' => $definition['key']],
                $definition
            );
            $count++;
        }

        Cache::forget('notification_templates');

        return $count;
    }

    protected static function findTemplate(string $key): ?NotificationTemplate
    {
        $templates = Cache::remember('notification_templates', 3600, function () {
            return NotificationTemplate::where('active', true)->get()->keyBy('key');
        });

        return $templates->get($key);
    }

    protected static function defaultBody(string $key): string
    {
        foreach (NotificationTemplateDefinitions::all() as $definition) {
            if ($definition['key'] === $key) {
                return $definition['body'];
            }
        }

        return $key;
    }
}
