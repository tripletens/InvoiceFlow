<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class RemindersSettings extends Component
{
    public string $plan = 'starter';

    // Reminder settings (Pro & Agency)
    public bool $reminder3days = true;
    public bool $reminder1day = true;
    public bool $reminderOnDue = true;
    public bool $reminderOverdue = true;
    public int $overdueIntervalDays = 7;

    public function mount(): void
    {
        $this->plan = auth()->user()->currentPlan();
    }

    public function save(): void
    {
        if ($this->plan === 'starter') {
            session()->flash('error', 'Automated reminders require a Pro or Agency plan.');
            return;
        }

        session()->flash('success', 'Reminder preferences saved successfully!');
    }

    public function render()
    {
        return view('livewire.settings.reminders-settings')
            ->layout('layouts.app', ['title' => 'Automated Reminders — InvoiceFlow']);
    }
}
