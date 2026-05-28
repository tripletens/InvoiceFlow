<?php

namespace App\Livewire\Admin;

use App\Models\Invoice;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AdminDashboard extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterPlan = '';
    public string $activeGateway = 'stripe';

    public function mount()
    {
        $this->activeGateway = \App\Models\Setting::where('key', 'active_payment_gateway')->value('value') ?? 'stripe';
    }

    public function updatedActiveGateway($value)
    {
        \App\Models\Setting::updateOrCreate(
            ['key' => 'active_payment_gateway'],
            ['value' => $value]
        );
        session()->flash('success', 'Active payment gateway updated to ' . ucfirst($value));
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function toggleSuspend(int $userId): void
    {
        $user = User::findOrFail($userId);
        abort_if($user->id === auth()->id(), 403, 'Cannot suspend yourself.');
        $user->update(['is_admin' => ! $user->is_admin]);
    }

    public function render()
    {
        $users = User::when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->withCount('invoices')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_users'    => User::count(),
            'total_admins'   => User::where('is_admin', true)->count(),
            'total_invoices' => Invoice::count(),
            'paid_invoices'  => Invoice::where('status', 'paid')->count(),
            'total_revenue'  => Invoice::where('status', 'paid')->sum('total'),
        ];

        return view('livewire.admin.admin-dashboard', compact('users', 'stats'))
            ->layout('layouts.app', ['title' => 'Admin — InvoiceFlow']);
    }
}
