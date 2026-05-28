@php use App\Helpers\CurrencyHelper; @endphp
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header --}}
        <div>
            <a href="{{ route('invoices.index') }}" class="text-slate-400 hover:text-white text-sm transition-all">← Back to Invoices</a>
            <h1 class="text-2xl font-bold text-white mt-2">Create New Invoice</h1>
        </div>

        <form wire:submit="save" class="space-y-6">

            {{-- Client & Dates --}}
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-200 border-b border-slate-800 pb-3">Invoice Details</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Client *</label>
                        <select wire:model="client_id" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40">
                            <option value="">Select a client...</option>
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} {{ $client->company ? "({$client->company})" : '' }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Currency</label>
                        <select wire:model="currency" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40">
                            <option value="USD">USD — US Dollar</option>
                            <option value="EUR">EUR — Euro</option>
                            <option value="GBP">GBP — British Pound</option>
                            <option value="NGN">NGN — Nigerian Naira</option>
                            <option value="CAD">CAD — Canadian Dollar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Issue Date *</label>
                        <input wire:model="issue_date" type="date" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        @error('issue_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Due Date *</label>
                        <input wire:model="due_date" type="date" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        @error('due_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Line Items --}}
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-200 border-b border-slate-800 pb-3">Line Items</h2>

                <div class="space-y-3">
                    @foreach($items as $i => $item)
                    <div class="grid grid-cols-12 gap-3 items-start">
                        <div class="col-span-6">
                            @if($loop->first)
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Description *</label>
                            @endif
                            <input wire:model.live="items.{{ $i }}.description" type="text" placeholder="Service or product description"
                                class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                            @error("items.{$i}.description") <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            @if($loop->first)
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Qty *</label>
                            @endif
                            <input wire:model.live="items.{{ $i }}.quantity" type="number" step="0.01" min="0.01"
                                class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        </div>
                        <div class="col-span-3">
                            @if($loop->first)
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Unit Price *</label>
                            @endif
                            <input wire:model.live="items.{{ $i }}.unit_price" type="number" step="0.01" min="0"
                                class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        </div>
                        <div class="col-span-1 flex items-end {{ $loop->first ? 'pt-6' : '' }}">
                            @if(count($items) > 1)
                            <button type="button" wire:click="removeItem({{ $i }})" class="p-2.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-all w-full flex items-center justify-center">
                                &times;
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" wire:click="addItem" class="mt-2 px-4 py-2 rounded-lg bg-slate-800 border border-slate-700 text-slate-300 text-sm font-semibold hover:bg-slate-700 transition-all">
                    + Add Line Item
                </button>

                {{-- Totals --}}
                <div class="mt-4 pt-4 border-t border-slate-800 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Subtotal</span>
                        <span class="font-semibold text-slate-200">{{ CurrencyHelper::format($this->subtotal, $currency) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm gap-4">
                        <span class="text-slate-400">Tax Rate (%)</span>
                        <input wire:model.live="tax_rate" type="number" step="0.1" min="0" max="100"
                            class="w-24 px-2 py-1 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm text-right focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Tax Amount</span>
                        <span class="text-slate-300">{{ CurrencyHelper::format($this->taxAmount, $currency) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-slate-700">
                        <span class="font-bold text-white">Total</span>
                        <span class="text-xl font-black text-cyan-400">{{ CurrencyHelper::format($this->total, $currency) }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Notes (optional)</label>
                <textarea wire:model="notes" rows="3" placeholder="Any additional notes for your client..."
                    class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40"></textarea>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold shadow-lg transition-all">
                    Create Invoice
                </button>
                <a href="{{ route('invoices.index') }}" class="px-6 py-3 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 font-semibold hover:bg-slate-700 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
