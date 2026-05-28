<?php

namespace App\Livewire;

use App\Services\InvoiceService;
use Livewire\Component;

class Dashboard extends Component
{
    public array $stats = [];
    public array $monthlyLabels = [];
    public array $monthlyValues = [];
    public array $statusCounts = [];
    public float $totalExpenses = 0;
    public float $netProfit = 0;

    public function mount(InvoiceService $invoiceService): void
    {
        $userId = auth()->id();
        $this->stats = $invoiceService->getDashboardStats($userId);

        // Fetch status distribution counts
        $invoices = \App\Models\Invoice::where('user_id', $userId)->get();
        $this->statusCounts = [
            'Paid' => $invoices->where('status', 'paid')->count(),
            'Sent' => $invoices->where('status', 'sent')->count() + $invoices->where('status', 'viewed')->count(),
            'Overdue' => $invoices->where('status', 'overdue')->count(),
            'Draft' => $invoices->where('status', 'draft')->count(),
        ];

        // Fetch expenses & net profit
        $this->totalExpenses = (float) \App\Models\Expense::where('user_id', $userId)->sum('amount');
        $totalPaidRevenue = (float) \App\Models\Invoice::where('user_id', $userId)->where('status', 'paid')->sum('total');
        $this->netProfit = $totalPaidRevenue - $this->totalExpenses;

        // Fetch last 6 months revenue
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $this->monthlyLabels[] = $date->format('M Y');
            $this->monthlyValues[] = (float) \App\Models\Invoice::where('user_id', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', '!=', 'draft')
                ->sum('total');
        }
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app', ['title' => 'Dashboard — InvoiceFlow']);
    }
}
