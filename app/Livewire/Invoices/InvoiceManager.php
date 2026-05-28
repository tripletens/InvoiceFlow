<?php

namespace App\Livewire\Invoices;

use App\Models\Client;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

use Illuminate\Support\Facades\Hash;

class InvoiceManager extends Component
{
    use WithPagination;

    #[Url(as: 'status')]
    public string $filterStatus = '';
    public string $search = '';

    // PIN confirmation variables
    public bool $confirmingDelete = false;
    public ?int $confirmingDeleteId = null;
    public string $confirmPinInput = '';

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updateStatus(int $id, string $status, InvoiceService $invoiceService): void
    {
        $invoice = Invoice::findOrFail($id);
        $invoiceService->updateStatus($invoice, auth()->id(), $status);
        session()->flash('success', "Invoice marked as {$status}.");
    }

    public function confirmDelete(int $id): void
    {
        if (empty(auth()->user()->security_pin)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Please set up a Security PIN in your Profile first.']);
            return;
        }

        $this->confirmingDeleteId = $id;
        $this->confirmPinInput = '';
        $this->confirmingDelete = true;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = false;
        $this->confirmingDeleteId = null;
        $this->confirmPinInput = '';
    }

    public function verifyAndPerformDelete(InvoiceService $invoiceService): void
    {
        if (!$this->confirmingDeleteId) {
            return;
        }

        if (!Hash::check($this->confirmPinInput, auth()->user()->security_pin)) {
            $this->addError('confirmPinInput', 'Incorrect Security PIN.');
            return;
        }

        $invoice = Invoice::findOrFail($this->confirmingDeleteId);
        $invoiceService->deleteInvoice($invoice, auth()->id());
        
        $this->confirmingDelete = false;
        $this->confirmingDeleteId = null;
        $this->confirmPinInput = '';

        session()->flash('success', 'Invoice deleted successfully.');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Invoice deleted successfully.']);
    }

    public function exportCsv()
    {
        $invoices = Invoice::with('client')
            ->where('user_id', auth()->id())
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, fn($q) => $q->whereHas('client', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })->orWhere('invoice_number', 'like', "%{$this->search}%"))
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="invoices_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Invoice #', 
                'Client Name', 
                'Client Email', 
                'Client Company', 
                'Subtotal', 
                'Tax Rate (%)', 
                'Tax Amount', 
                'Total', 
                'Currency', 
                'Status', 
                'Issue Date', 
                'Due Date', 
                'Notes'
            ]);

            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->invoice_number,
                    $invoice->client->name,
                    $invoice->client->email,
                    $invoice->client->company ?? '',
                    $invoice->subtotal,
                    $invoice->tax_rate,
                    $invoice->tax_amount,
                    $invoice->total,
                    $invoice->currency,
                    ucfirst($invoice->status),
                    $invoice->issue_date->format('Y-m-d'),
                    $invoice->due_date->format('Y-m-d'),
                    $invoice->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, 'invoices_' . now()->format('Y-m-d') . '.csv', $headers);
    }

    public function exportExcel()
    {
        $invoices = Invoice::with('client')
            ->where('user_id', auth()->id())
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, fn($q) => $q->whereHas('client', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })->orWhere('invoice_number', 'like', "%{$this->search}%"))
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="invoices_' . now()->format('Y-m-d') . '.xls"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = function () use ($invoices) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
            echo '<body>';
            echo '<table border="1">';
            echo '<tr>';
            echo '<th>Invoice #</th>';
            echo '<th>Client Name</th>';
            echo '<th>Client Email</th>';
            echo '<th>Client Company</th>';
            echo '<th>Subtotal</th>';
            echo '<th>Tax Rate (%)</th>';
            echo '<th>Tax Amount</th>';
            echo '<th>Total</th>';
            echo '<th>Currency</th>';
            echo '<th>Status</th>';
            echo '<th>Issue Date</th>';
            echo '<th>Due Date</th>';
            echo '<th>Notes</th>';
            echo '</tr>';

            foreach ($invoices as $invoice) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($invoice->invoice_number) . '</td>';
                echo '<td>' . htmlspecialchars($invoice->client->name) . '</td>';
                echo '<td>' . htmlspecialchars($invoice->client->email) . '</td>';
                echo '<td>' . htmlspecialchars($invoice->client->company ?? '') . '</td>';
                echo '<td>' . number_format($invoice->subtotal, 2, '.', '') . '</td>';
                echo '<td>' . number_format($invoice->tax_rate, 2, '.', '') . '</td>';
                echo '<td>' . number_format($invoice->tax_amount, 2, '.', '') . '</td>';
                echo '<td>' . number_format($invoice->total, 2, '.', '') . '</td>';
                echo '<td>' . htmlspecialchars($invoice->currency) . '</td>';
                echo '<td>' . htmlspecialchars(ucfirst($invoice->status)) . '</td>';
                echo '<td>' . $invoice->issue_date->format('Y-m-d') . '</td>';
                echo '<td>' . $invoice->due_date->format('Y-m-d') . '</td>';
                echo '<td>' . htmlspecialchars($invoice->notes ?? '') . '</td>';
                echo '</tr>';
            }

            echo '</table>';
            echo '</body>';
            echo '</html>';
        };

        return response()->streamDownload($callback, 'invoices_' . now()->format('Y-m-d') . '.xls', $headers);
    }

    public function downloadPdf(int $id)
    {
        $invoice = Invoice::with(['client', 'items'])->where('user_id', auth()->id())->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('invoice'));
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'invoice_' . $invoice->invoice_number . '.pdf'
        );
    }

    public function render()
    {
        $invoices = Invoice::with('client')
            ->where('user_id', auth()->id())
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, fn($q) => $q->whereHas('client', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })->orWhere('invoice_number', 'like', "%{$this->search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.invoices.invoice-manager', compact('invoices'))
            ->layout('layouts.app', ['title' => 'Invoices — InvoiceFlow']);
    }
}
