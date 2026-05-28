<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public string $old_pin = '';
    public string $pin = '';
    public string $pin_confirmation = '';

    public bool $hasPin = false;
    public string $default_currency = 'USD';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->default_currency = $user->default_currency ?? 'USD';
        $this->hasPin = !empty($user->security_pin);
    }

    public function updateProfile(): void
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'default_currency' => 'required|string|max:3',
        ];

        if ($this->password) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $this->validate($rules);

        $user->name = $this->name;
        $user->email = $this->email;
        $user->default_currency = $this->default_currency;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->reset(['password', 'password_confirmation']);
        
        session()->flash('success', 'Profile updated successfully.');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Profile updated successfully.']);
    }

    public function updatePin(): void
    {
        $user = auth()->user();

        $rules = [];

        if ($this->hasPin) {
            $rules['old_pin'] = [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->security_pin)) {
                        $fail('The current security PIN is incorrect.');
                    }
                }
            ];
        }

        $rules['pin'] = 'required|numeric|digits_between:4,6|confirmed';

        $this->validate($rules);

        $user->security_pin = Hash::make($this->pin);
        $user->save();

        $this->hasPin = true;
        $this->reset(['old_pin', 'pin', 'pin_confirmation']);

        session()->flash('success', 'Security PIN saved successfully.');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Security PIN saved successfully.']);
    }

    public function render()
    {
        return view('livewire.profile')
            ->layout('layouts.app', ['title' => 'Profile — InvoiceFlow']);
    }
}
