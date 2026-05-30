<?php

namespace App\Livewire\Settings;

use App\Models\Webhook;
use Illuminate\Support\Str;
use Livewire\Component;

class WebhookManager extends Component
{
    public $webhooks;
    public $business;

    public $showCreateModal = false;
    public $url = '';
    public $events = [];

    public $availableEvents = [
        'invoice.created' => 'Invoice Created',
        'invoice.paid' => 'Invoice Paid',
        'invoice.overdue' => 'Invoice Overdue',
        'client.created' => 'Client Created',
    ];

    protected $rules = [
        'url' => 'required|url|max:255',
        'events' => 'required|array|min:1',
    ];

    public function mount()
    {
        $this->business = auth()->user()->businesses()->first();
        if (!$this->business) {
            session()->flash('error', 'You must have a business profile to manage webhooks.');
            $this->redirectRoute('dashboard');
            return;
        }

        $this->loadWebhooks();
    }

    public function loadWebhooks()
    {
        $this->webhooks = $this->business->webhooks()->get();
    }

    public function createWebhook()
    {
        $this->validate();

        $this->business->webhooks()->create([
            'url' => $this->url,
            'events' => $this->events,
            'secret' => Str::random(32),
            'is_active' => true,
        ]);

        $this->reset(['url', 'events', 'showCreateModal']);
        $this->loadWebhooks();
        session()->flash('success', 'Webhook created successfully!');
    }

    public function toggleStatus(int $id)
    {
        $webhook = $this->business->webhooks()->findOrFail($id);
        $webhook->update(['is_active' => !$webhook->is_active]);
        $this->loadWebhooks();
    }

    public function deleteWebhook(int $id)
    {
        $this->business->webhooks()->findOrFail($id)->delete();
        $this->loadWebhooks();
        session()->flash('success', 'Webhook deleted successfully.');
    }

    public function render()
    {
        return view('livewire.settings.webhook-manager')->layout('layouts.app', ['title' => 'Webhooks']);
    }
}
