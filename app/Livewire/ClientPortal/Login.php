<?php

namespace App\Livewire\ClientPortal;

use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    
    // For first-time password setup
    public bool $isFirstTime = false;
    public string $newPassword = '';
    public string $confirmNewPassword = '';

    protected array $rules = [
        'email' => 'required|email|exists:clients,email',
    ];

    public function handleLogin(): void
    {
        $this->validate();

        $client = Client::where('email', $this->email)->first();

        if (!$client->portal_enabled) {
            $this->addError('email', 'Portal access is disabled for this account.');
            return;
        }

        // If client has no password, prompt to set one
        if (empty($client->password)) {
            $this->isFirstTime = true;
            return;
        }

        $this->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($this->password, $client->password)) {
            $this->addError('password', 'Invalid credentials.');
            return;
        }

        session(['client_id' => $client->id]);
        session()->flash('success', 'Logged in to client portal.');
        $this->redirectRoute('client.dashboard');
    }

    public function setupPassword(): void
    {
        $this->validate([
            'newPassword' => 'required|string|min:6',
            'confirmNewPassword' => 'required|same:newPassword',
        ]);

        $client = Client::where('email', $this->email)->first();
        if ($client) {
            $client->update([
                'password' => Hash::make($this->newPassword),
            ]);

            session(['client_id' => $client->id]);
            session()->flash('success', 'Password set up successfully! Welcome to your dashboard.');
            $this->redirectRoute('client.dashboard');
        }
    }

    public function render()
    {
        return view('livewire.client-portal.login')
            ->layout('layouts.guest', ['title' => 'Client Portal Login — InvoiceFlow']);
    }
}
