<?php

namespace App\Livewire\ClientPortal;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class PortalDashboard extends Component
{
    public ?Client $client = null;

    // Password reset fields
    public string $password = '';
    public string $password_confirmation = '';
    public bool $isEditingSettings = false;

    public function mount(): void
    {
        $clientId = session('client_id');
        if (!$clientId) {
            $this->redirectRoute('client.login');
            return;
        }

        $this->client = Client::findOrFail($clientId);
    }

    public function logout(): void
    {
        session()->forget('client_id');
        session()->flash('success', 'You have logged out of the portal.');
        $this->redirectRoute('client.login');
    }

    public function toggleSettings(): void
    {
        $this->isEditingSettings = !$this->isEditingSettings;
        $this->reset(['password', 'password_confirmation']);
    }

    public function updatePassword(): void
    {
        $this->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $this->client->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['password', 'password_confirmation']);
        $this->isEditingSettings = false;
        
        session()->flash('success', 'Portal access password updated successfully!');
    }

    public function downloadPdf(int $id)
    {
        $invoice = Invoice::where('client_id', $this->client->id)->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('invoice'));
        
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'invoice_' . $invoice->invoice_number . '.pdf'
        );
    }

    public function render()
    {
        $invoices = Invoice::where('client_id', $this->client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate KPI values
        $outstanding = $invoices->whereIn('status', ['sent', 'viewed', 'overdue'])->sum('total');
        $paid = $invoices->where('status', 'paid')->sum('total');
        $totalInvoicesCount = $invoices->count();

        // Get the business issuing the invoices to show bank details
        $business = $invoices->first()?->business ?? $this->client->user->businesses->first();

        return view('livewire.client-portal.portal-dashboard', compact('invoices', 'outstanding', 'paid', 'totalInvoicesCount', 'business'))
            ->layout('layouts.client', ['title' => 'Client Portal — ' . ($this->client->company ?? $this->client->name)]);
    }
}
