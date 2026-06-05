<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class N8NWebhookService
{
    public static function send(string $event, $data): void
    {
        $webhookUrl = config('services.n8n.webhook_url');

        if (!$webhookUrl) {
            return;
        }

        try {
            Http::timeout(5)->post($webhookUrl, [
                'event' => $event,
                'data' => method_exists($data, 'toArray') ? $data->toArray() : $data,
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Throwable $e) {
            // silently fail
        }
    }
}
