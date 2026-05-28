<?php

namespace App\Livewire\RecurringInvoices;

use App\Models\Client;
use App\Models\RecurringInvoice;
use App\Models\RecurringInvoiceItem;
use App\Console\Commands\GenerateRecurringInvoices;
use Livewire\Component;
use Livewire\WithPagination;

class RecurringInvoiceManager extends Component
{
    use WithPagination;

    public bool $isCreating = false;

    // Form fields
    public int $client_id = 0;
    public string $frequency = 'monthly';
    public string $next_generation_date = '';
    public $tax_rate = 0;
    public string $currency = 'USD';
    public string $notes = '';
    public array $items = [
        ['description' => '', 'quantity' => 1, 'unit_price' => 0],
    ];

    protected array $rules = [
        'client_id'  => 'required|exists:clients,id',
        'frequency'  => 'required|in:daily,weekly,monthly,yearly',
        'next_generation_date' => 'required|date',
        'tax_rate'   => 'nullable|numeric|min:0|max:100',
        'currency'   => 'required|string|max:3',
        'items'      => 'required|array|min:1',
        'items.*.description' => 'required|string',
        'items.*.quantity'    => 'required|numeric|min:0.01',
        'items.*.unit_price'  => 'required|numeric|min:0',
    ];

    public function mount(): void
    {
        $this->next_generation_date = now()->addDay()->toDateString();
        $this->currency = auth()->user()->default_currency ?? 'USD';
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

    public function toggleCreate(): void
    {
        $this->isCreating = !$this->isCreating;
        if ($this->isCreating) {
            $this->resetForm();
        }
    }

    public function resetForm(): void
    {
        $this->client_id = 0;
        $this->frequency = 'monthly';
        $this->next_generation_date = now()->addDay()->toDateString();
        $this->tax_rate = 0;
        $this->currency = 'USD';
        $this->notes = '';
        $this->items = [
            ['description' => '', 'quantity' => 1, 'unit_price' => 0],
        ];
    }

    public function toggleStatus(int $id): void
    {
        $rec = RecurringInvoice::where('user_id', auth()->id())->findOrFail($id);
        $newStatus = $rec->status === 'active' ? 'paused' : 'active';
        $rec->update(['status' => $newStatus]);
        session()->flash('success', "Recurring schedule is now {$newStatus}.");
    }

    public function delete(int $id): void
    {
        $rec = RecurringInvoice::where('user_id', auth()->id())->findOrFail($id);
        $rec->delete();
        session()->flash('success', "Recurring schedule deleted.");
    }

    public function runScheduler(): void
    {
        // Call the command directly
        $command = new GenerateRecurringInvoices();
        // Since it's in web context, we can just run the logic or resolve via Artisan facade
        \Artisan::call('app:generate-recurring-invoices');
        
        session()->flash('success', 'Artisan billing scheduler executed! Any due recurring invoices have been generated.');
    }

    public function save(): void
    {
        $this->validate();

        $subtotal = $this->subtotal;
        $taxAmount = $this->taxAmount;
        $total = $this->total;

        $rec = RecurringInvoice::create([
            'user_id' => auth()->id(),
            'client_id' => $this->client_id,
            'frequency' => $this->frequency,
            'status' => 'active',
            'subtotal' => $subtotal,
            'tax_rate' => $this->tax_rate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'currency' => $this->currency,
            'notes' => $this->notes,
            'next_generation_date' => $this->next_generation_date,
        ]);

        foreach ($this->items as $item) {
            RecurringInvoiceItem::create([
                'recurring_invoice_id' => $rec->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => (float)$item['quantity'] * (float)$item['unit_price'],
            ]);
        }

        session()->flash('success', 'Recurring invoice schedule created successfully!');
        $this->resetForm();
        $this->isCreating = false;
    }

    public function render()
    {
        $recurringInvoices = RecurringInvoice::with(['client', 'items'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $clients = Client::where('user_id', auth()->id())->orderBy('name')->get();

        // Calculate MRR (Monthly Recurring Revenue)
        $mrr = 0;
        $activeRecurring = RecurringInvoice::where('user_id', auth()->id())
            ->where('status', 'active')
            ->get();
        
        foreach ($activeRecurring as $rec) {
            $monthlyValue = match($rec->frequency) {
                'daily' => $rec->total * 30,
                'weekly' => $rec->total * 4.33,
                'monthly' => $rec->total,
                'yearly' => $rec->total / 12,
                default => $rec->total
            };
            $mrr += $monthlyValue;
        }

        return view('livewire.recurring-invoices.recurring-invoice-manager', compact('recurringInvoices', 'clients', 'mrr'))
            ->layout('layouts.app', ['title' => 'Recurring Invoices — InvoiceFlow']);
    }
}
