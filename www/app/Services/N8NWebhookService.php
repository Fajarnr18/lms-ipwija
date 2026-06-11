<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class N8NWebhookService
{
    public static function send(string $event, array $payload): void
    {
        $webhookUrl = config('services.n8n.webhook_url');

        if (!$webhookUrl) {
            return;
        }

        try {
            Http::timeout(5)->post($webhookUrl, [
                'event' => $event,
                ...$payload,
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Throwable $e) {
            logger()->error('[N8N Webhook] Failed: ' . $e->getMessage());
        }
    }
}
