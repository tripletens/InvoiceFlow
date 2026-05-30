<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Jobs\SendWebhookJob;
use App\Models\Business;
use App\Models\Invoice;
use App\Models\Client;

class DispatchWebhooks
{
    public function handle($event): void
    {
        $eventName = $this->getEventName($event);
        $business = $this->getBusinessFromEvent($event);
        $payload = $this->getPayloadFromEvent($event, $eventName);

        if (!$eventName || !$business || !$payload) {
            return;
        }

        // Find all active webhooks for this business that are subscribed to this event
        $webhooks = $business->webhooks()->where('is_active', true)->get();

        foreach ($webhooks as $webhook) {
            $subscribedEvents = $webhook->events ?? [];
            
            if (in_array($eventName, $subscribedEvents) || in_array('*', $subscribedEvents)) {
                // Dispatch the job to send the webhook
                SendWebhookJob::dispatch($webhook, $eventName, $payload);
            }
        }
    }

    private function getEventName($event): ?string
    {
        return match (get_class($event)) {
            \App\Events\InvoiceCreated::class => 'invoice.created',
            \App\Events\InvoicePaid::class => 'invoice.paid',
            \App\Events\InvoiceOverdue::class => 'invoice.overdue',
            \App\Events\ClientCreated::class => 'client.created',
            default => null,
        };
    }

    private function getBusinessFromEvent($event): ?Business
    {
        if (isset($event->invoice) && $event->invoice instanceof Invoice) {
            return $event->invoice->user->businesses()->first();
        }

        if (isset($event->client) && $event->client instanceof Client) {
            return $event->client->user->businesses()->first();
        }

        return null;
    }

    private function getPayloadFromEvent($event, $eventName): ?array
    {
        if (isset($event->invoice) && $event->invoice instanceof Invoice) {
            $invoice = $event->invoice;
            $invoice->loadMissing(['client', 'items']);
            return [
                'event' => $eventName,
                'data' => [
                    'invoice' => $invoice->toArray(),
                ],
                'timestamp' => now()->toIso8601String(),
            ];
        }

        if (isset($event->client) && $event->client instanceof Client) {
            return [
                'event' => $eventName,
                'data' => [
                    'client' => $event->client->toArray(),
                ],
                'timestamp' => now()->toIso8601String(),
            ];
        }

        return null;
    }
}
