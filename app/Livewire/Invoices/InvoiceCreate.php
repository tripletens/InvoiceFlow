<?php

namespace App\Livewire\Invoices;

use App\Models\Client;
use App\Services\InvoiceService;
use Livewire\Component;

class InvoiceCreate extends Component
{
    public int $client_id   = 0;
    public string $issue_date = '';
    public string $due_date   = '';
    public $tax_rate    = 0;
    public string $currency   = 'USD';
    public string $notes      = '';

    public array $items = [
        ['description' => '', 'quantity' => 1, 'unit_price' => 0],
    ];

    protected array $rules = [
        'client_id'  => 'required|exists:clients,id',
        'issue_date' => 'required|date',
        'due_date'   => 'required|date|after_or_equal:issue_date',
        'tax_rate'   => 'nullable|numeric|min:0|max:100',
        'currency'   => 'required|string|max:3',
        'items'      => 'required|array|min:1',
        'items.*.description' => 'required|string',
        'items.*.quantity'    => 'required|numeric|min:0.01',
        'items.*.unit_price'  => 'required|numeric|min:0',
    ];

    public function mount(): void
    {
        if (!auth()->user()->canCreateInvoice()) {
            session()->flash('error', 'You have reached your limit of 3 invoices per month on the Free Starter plan. Please upgrade to Pro to create more.');
            $this->redirect(route('upgrade'), navigate: true);
            return;
        }

        $this->issue_date = now()->toDateString();
        $this->due_date   = now()->addDays(30)->toDateString();
        $this->currency   = auth()->user()->default_currency ?? 'USD';
    }

    public function addItem(): void
    {
        $this->items[] = ['description' => '', 'quantity' => 1, 'unit_price' => 0];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->items)->sum(fn($i) => (float)($i['quantity'] ?? 0) * (float)($i['unit_price'] ?? 0));
    }

    public function getTaxAmountProperty(): float
    {
        return $this->subtotal * ((float)$this->tax_rate / 100);
    }

    public function getTotalProperty(): float
    {
        return $this->subtotal + $this->taxAmount;
    }

    public function save(InvoiceService $invoiceService): void
    {
        $this->validate();

        $invoice = $invoiceService->createInvoice(
            auth()->id(),
            [
                'client_id'  => $this->client_id,
                'issue_date' => $this->issue_date,
                'due_date'   => $this->due_date,
                'tax_rate'   => $this->tax_rate,
                'currency'   => $this->currency,
                'notes'      => $this->notes,
            ],
            $this->items
        );

        session()->flash('success', "Invoice #{$invoice->invoice_number} created successfully!");
        $this->redirectRoute('invoices.index');
    }

    public function render()
    {
        $clients = Client::where('user_id', auth()->id())->orderBy('name')->get();

        return view('livewire.invoices.invoice-create', compact('clients'))
            ->layout('layouts.app', ['title' => 'Create Invoice — InvoiceFlow']);
    }
}
