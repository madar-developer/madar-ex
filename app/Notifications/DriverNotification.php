<?php

namespace App\Notifications;

use App\Http\Controllers\Api\FCMController;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DriverNotification extends Notification
{
    use Queueable;

    private $titleAr;
    private $titleEn;
    private $contentAr;
    private $contentEn;
    private $type;
    private $relatedId;
    private $redirect;
    private $activity;

    public function __construct(
        string $titleAr,
        string $contentAr,
        string $type,
        $relatedId = null,
        string $redirect = '#',
        ?string $titleEn = null,
        ?string $contentEn = null,
        ?string $activity = null
    ) {
        $this->titleAr = $titleAr;
        $this->contentAr = $contentAr;
        $this->titleEn = $titleEn ?: $titleAr;
        $this->contentEn = $contentEn ?: $contentAr;
        $this->type = $type;
        $this->relatedId = $relatedId;
        $this->redirect = $redirect;
        $this->activity = $activity ?: $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $locale = app()->getLocale();
        $title = $locale === 'en' ? $this->titleEn : $this->titleAr;
        $content = $locale === 'en' ? $this->contentEn : $this->contentAr;

        $payload = [
            'title_ar' => $this->titleAr,
            'title_en' => $this->titleEn,
            'content_ar' => $this->contentAr,
            'content_en' => $this->contentEn,
            'type' => $this->type,
            'related_id' => $this->relatedId,
        ];

        if ($this->type === 'order' && $this->relatedId) {
            $payload['order_id'] = $this->relatedId;
        }

        try {
            $tokens = $notifiable->PlayerId()->pluck('player_id')->toArray();
            if (!empty($tokens)) {
                \Log::info('DriverNotification FCM tokens', [
                    'driver_id' => $notifiable->id ?? null,
                    'tokens' => $tokens,
                ]);
                FCMController::Push($title, $content, $tokens, $payload, $this->activity);
            }
        } catch (\Throwable $e) {
            \Log::warning('DriverNotification FCM failed', [
                'driver_id' => $notifiable->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        return [
            'text' => $content,
            'related_id' => $this->relatedId,
            'type' => $this->type,
            'redirect' => $this->redirect,
        ];
    }

    public function toDatabase($notifiable)
    {
        return $this->toArray($notifiable);
    }
}
