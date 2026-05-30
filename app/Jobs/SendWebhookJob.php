<?php

namespace App\Jobs;

use App\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $webhook;
    public $eventName;
    public $payload;

    public $tries = 3;
    public $backoff = [10, 60, 300]; // retry after 10s, 60s, 300s

    public function __construct(Webhook $webhook, string $eventName, array $payload)
    {
        $this->webhook = $webhook;
        $this->eventName = $eventName;
        $this->payload = $payload;
    }

    public function handle(): void
    {
        $signature = hash_hmac('sha256', json_encode($this->payload), $this->webhook->secret);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-InvoiceFlow-Event' => $this->eventName,
                'X-InvoiceFlow-Signature' => $signature,
            ])->timeout(10)->post($this->webhook->url, $this->payload);

            if ($response->failed()) {
                Log::warning("Webhook failed to send", [
                    'webhook_id' => $this->webhook->id,
                    'url' => $this->webhook->url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                
                // Throw exception so the queue worker knows it failed and will retry
                $response->throw();
            }

            Log::info("Webhook sent successfully", [
                'webhook_id' => $this->webhook->id,
                'event' => $this->eventName,
            ]);

        } catch (\Exception $e) {
            Log::error("Webhook exception: " . $e->getMessage(), [
                'webhook_id' => $this->webhook->id,
                'url' => $this->webhook->url,
            ]);
            
            throw $e;
        }
    }
}
