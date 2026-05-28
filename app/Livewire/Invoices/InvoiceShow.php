<?php

namespace App\Livewire\Invoices;

use App\Mail\InvoiceSent;
use App\Services\InvoiceService;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class InvoiceShow extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice, InvoiceService $invoiceService): void
    {
        $this->invoice = $invoice->load(['client', 'items']);
        
        // Ensure user owns this invoice
        if ($this->invoice->user_id !== auth()->id()) {
            abort(403);
        }
    }

    public function sendEmail(InvoiceService $invoiceService): void
    {
        // Send email via Mail facade
        Mail::to($this->invoice->client->email)->send(new InvoiceSent($this->invoice));
        
        // Update status to 'sent' if it is a draft
        if ($this->invoice->status === 'draft') {
            $invoiceService->updateStatus($this->invoice, auth()->id(), 'sent');
            $this->invoice->refresh();
        }

        session()->flash('success', 'Invoice emailed to client successfully.');
    }

    public function markAsPaid(InvoiceService $invoiceService): void
    {
        $invoiceService->updateStatus($this->invoice, auth()->id(), 'paid');
        $this->invoice->refresh();
        session()->flash('success', 'Invoice marked as paid.');
    }

    public function downloadPdf()
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['invoice' => $this->invoice]);
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'invoice_' . $this->invoice->invoice_number . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.invoices.invoice-show')
            ->layout('layouts.app', ['title' => 'Invoice ' . $this->invoice->invoice_number . ' — InvoiceFlow']);
    }
}
