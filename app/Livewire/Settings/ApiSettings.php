<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class ApiSettings extends Component
{
    public ?string $token = null;
    public bool $tokenVisible = false;
    public string $plan = 'starter';

    public function mount(): void
    {
        $this->plan = auth()->user()->currentPlan();

        if ($this->plan === 'starter') {
            // Starter users can see the page but not generate tokens — handled in view
        }
    }

    public function generateToken(): void
    {
        if (auth()->user()->currentPlan() === 'starter') {
            session()->flash('error', 'API access requires a Pro or Agency plan.');
            return;
        }

        $token = auth()->user()->createToken('api-token')->plainTextToken;
        $this->token = $token;
        $this->tokenVisible = true;
        session()->flash('success', 'New API token generated. Copy it now — it will not be shown again.');
    }

    public function revokeAllTokens(): void
    {
        auth()->user()->tokens()->delete();
        $this->token = null;
        $this->tokenVisible = false;
        session()->flash('success', 'All API tokens have been revoked.');
    }

    public function render()
    {
        $tokenCount = auth()->user()->tokens()->count();

        return view('livewire.settings.api-settings', compact('tokenCount'))
            ->layout('layouts.app', ['title' => 'API Settings — InvoiceFlow']);
    }
}
