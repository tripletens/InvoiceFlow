@php use App\Helpers\CurrencyHelper; @endphp
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header & Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('invoices.index') }}" class="text-slate-400 hover:text-white text-sm transition-all">&larr; Back to Invoices</a>
                <h1 class="text-2xl font-bold text-white mt-2">Invoice {{ $invoice->invoice_number }}</h1>
                <p class="{{ CurrencyHelper::statusColor($invoice->status) }} font-medium text-sm mt-1">
                    {{ CurrencyHelper::statusLabel($invoice->status) }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if($invoice->status !== 'paid')
                <button wire:click="markAsPaid" class="px-4 py-2 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 font-semibold hover:bg-teal-500/20 transition-all text-sm">
                    Mark as Paid
                </button>
                @endif
                <button wire:click="downloadPdf" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold hover:text-white transition-all text-sm flex items-center gap-2">
                    📥 PDF
                </button>
                <button wire:click="sendEmail" class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold shadow-lg transition-all text-sm">
                    Email to Client
                </button>
            </div>
        </div>

        {{-- Invoice Document --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-8 sm:p-12 shadow-2xl shadow-slate-950/50">
            
            {{-- Top Info --}}
            <div class="flex flex-col sm:flex-row justify-between gap-8 border-b border-slate-800 pb-8">
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Billed To</h2>
                    <p class="text-lg font-bold text-white">{{ $invoice->client->name }}</p>
                    @if($invoice->client->company)
                    <p class="text-slate-300">{{ $invoice->client->company }}</p>
                    @endif
                    <p class="text-slate-400 text-sm">{{ $invoice->client->email }}</p>
                    @if($invoice->client->phone)
                    <p class="text-slate-400 text-sm">{{ $invoice->client->phone }}</p>
                    @endif
                    @if($invoice->client->address)
                    <p class="text-slate-400 text-sm mt-1 max-w-xs">{{ $invoice->client->address }}</p>
                    @endif
                </div>
                <div class="sm:text-right">
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Details</h2>
                    <div class="space-y-1 text-sm">
                        <p><span class="text-slate-400">Invoice No:</span> <span class="text-slate-200 font-medium">{{ $invoice->invoice_number }}</span></p>
                        <p><span class="text-slate-400">Issue Date:</span> <span class="text-slate-200">{{ $invoice->issue_date->format('M d, Y') }}</span></p>
                        <p><span class="text-slate-400">Due Date:</span> <span class="text-slate-200 font-medium">{{ $invoice->due_date->format('M d, Y') }}</span></p>
                    </div>
                </div>
            </div>

            {{-- Line Items Table --}}
            <div class="mt-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 uppercase tracking-wider text-xs">
                                <th class="py-3 font-semibold">Description</th>
                                <th class="py-3 font-semibold text-center">Qty</th>
                                <th class="py-3 font-semibold text-right">Unit Price</th>
                                <th class="py-3 font-semibold text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @foreach($invoice->items as $item)
                            <tr>
                                <td class="py-4">{{ $item->description }}</td>
                                <td class="py-4 text-center">{{ $item->quantity }}</td>
                                <td class="py-4 text-right">{{ CurrencyHelper::format($item->unit_price, $invoice->currency) }}</td>
                                <td class="py-4 text-right font-medium text-slate-200">{{ CurrencyHelper::format($item->total, $invoice->currency) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Totals --}}
            <div class="mt-6 border-t border-slate-800 pt-6 flex justify-end">
                <div class="w-full sm:w-64 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Subtotal</span>
                        <span class="font-medium text-slate-200">{{ CurrencyHelper::format($invoice->subtotal, $invoice->currency) }}</span>
                    </div>
                    @if($invoice->tax_rate > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Tax ({{ $invoice->tax_rate }}%)</span>
                        <span class="font-medium text-slate-200">{{ CurrencyHelper::format($invoice->tax_amount, $invoice->currency) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-3 border-t border-slate-700">
                        <span class="font-bold text-white uppercase tracking-wider text-sm">Total Due</span>
                        <span class="text-2xl font-black text-cyan-400">{{ CurrencyHelper::format($invoice->total, $invoice->currency) }}</span>
                    </div>
                </div>
            </div>

            @if($invoice->notes)
            <div class="mt-12 pt-6 border-t border-slate-800">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Notes</h3>
                <p class="text-sm text-slate-300 whitespace-pre-line">{{ $invoice->notes }}</p>
            </div>
            @endif

        </div>

    </div>
</div>
