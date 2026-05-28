@php use App\Helpers\CurrencyHelper; @endphp
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Header & Title --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Recurring Invoices & Retainers 🔄</h1>
                <p class="text-slate-400 text-sm mt-1">Automate regular client billing with recurring invoice schedules.</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="runScheduler" class="px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-200 font-semibold text-sm transition-all flex items-center gap-2">
                    ⚡ Run Billing Scheduler
                </button>
                <button wire:click="toggleCreate" class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold text-sm shadow-lg transition-all">
                    {{ $isCreating ? '← Back to List' : '+ New Recurring Invoice' }}
                </button>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(!$isCreating)
            {{-- Metrics Grid --}}
            <div class="grid sm:grid-cols-3 gap-5">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-cyan-500/5 rounded-full filter blur-xl"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Monthly Recurring Revenue (MRR)</p>
                    <p class="text-3xl font-black text-cyan-400 mt-2">{{ CurrencyHelper::format($mrr, auth()->user()->default_currency ?? 'USD') }}</p>
                    <p class="text-xs text-slate-400 mt-1">Simulated value based on active schedules ({{ auth()->user()->default_currency ?? 'USD' }})</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/5 rounded-full filter blur-xl"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Retainers</p>
                    <p class="text-3xl font-black text-emerald-400 mt-2">
                        {{ $recurringInvoices->where('status', 'active')->count() }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1">Total active auto-generating contracts</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/5 rounded-full filter blur-xl"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Next Execution Date</p>
                    <p class="text-3xl font-black text-indigo-400 mt-2">
                        @php
                            $earliest = $recurringInvoices->where('status', 'active')->sortBy('next_generation_date')->first();
                        @endphp
                        {{ $earliest ? $earliest->next_generation_date->format('M d, Y') : 'None scheduled' }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1">Earliest upcoming auto-billing trigger</p>
                </div>
            </div>

            {{-- List View --}}
            <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
                <div class="p-6 border-b border-slate-800">
                    <h2 class="text-lg font-bold text-slate-200">Active Billing Contracts</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Pause or test generate specific client setups.</p>
                </div>

                @if($recurringInvoices->isEmpty())
                    <div class="p-12 text-center text-slate-500">
                        <span class="text-3xl block mb-3">🔄</span>
                        No recurring invoice schedules found. Click "New Recurring Invoice" to get started.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-slate-950 text-xs font-bold uppercase tracking-wider text-slate-400">
                                <tr>
                                    <th class="px-6 py-4">Client</th>
                                    <th class="px-6 py-4">Frequency</th>
                                    <th class="px-6 py-4">Total Amount</th>
                                    <th class="px-6 py-4">Last Run</th>
                                    <th class="px-6 py-4">Next Run</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800 bg-slate-900/40">
                                @foreach($recurringInvoices as $rec)
                                <tr class="hover:bg-slate-800/30 transition-all">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-white">{{ $rec->client->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $rec->client->company }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $freqColors = ['daily'=>'bg-indigo-500/10 text-indigo-400 border-indigo-500/20','weekly'=>'bg-teal-500/10 text-teal-400 border-teal-500/20','monthly'=>'bg-cyan-500/10 text-cyan-400 border-cyan-500/20','yearly'=>'bg-violet-500/10 text-violet-400 border-violet-500/20'];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-xs border uppercase tracking-wider font-semibold {{ $freqColors[$rec->frequency] ?? 'bg-slate-500/10' }}">
                                            {{ $rec->frequency }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-200">
                                        {{ CurrencyHelper::format($rec->total, $rec->currency) }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $rec->last_generated_at ? $rec->last_generated_at->format('Y-m-d') : 'Never' }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-indigo-400">
                                        {{ $rec->next_generation_date->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($rec->status === 'active')
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Active</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase bg-slate-500/10 text-slate-400 border border-slate-500/20">Paused</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button wire:click="toggleStatus({{ $rec->id }})" class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $rec->status === 'active' ? 'bg-amber-500/10 border border-amber-500/20 text-amber-400 hover:bg-amber-500/20' : 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500/20' }} transition-all">
                                            {{ $rec->status === 'active' ? 'Pause' : 'Resume' }}
                                        </button>
                                        <button wire:click="delete({{ $rec->id }})" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-all">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-slate-800">
                        {{ $recurringInvoices->links() }}
                    </div>
                @endif
            </div>
        @else
            {{-- Create/Form View --}}
            <div class="max-w-4xl mx-auto space-y-6">
                <form wire:submit="save" class="space-y-6">
                    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
                        <h2 class="text-base font-bold text-slate-200 border-b border-slate-800 pb-3">Recurring Schedule Details</h2>
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
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Frequency *</label>
                                <select wire:model="frequency" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40">
                                    <option value="daily">Daily (testing / demo)</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">First Generation Date *</label>
                                <input wire:model="next_generation_date" type="date" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                                @error('next_generation_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
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
                        </div>
                    </div>

                    {{-- Line Items --}}
                    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
                        <h2 class="text-base font-bold text-slate-200 border-b border-slate-800 pb-3">Retainer Line Items</h2>
                        
                        <div class="space-y-3">
                            @foreach($items as $i => $item)
                            <div class="grid grid-cols-12 gap-3 items-start">
                                <div class="col-span-6">
                                    @if($loop->first)
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Description *</label>
                                    @endif
                                    <input wire:model.live="items.{{ $i }}.description" type="text" placeholder="Retainer service description"
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
                                <input wire:model.live="tax_rate" type="number" min="0" max="100" class="w-20 px-2 py-1 text-right rounded bg-slate-800 border border-slate-700 text-slate-200 text-xs focus:outline-none focus:ring-1 focus:ring-cyan-500" />
                            </div>
                            @if($tax_rate > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">Tax ({{ $tax_rate }}%)</span>
                                <span class="font-semibold text-slate-200">{{ CurrencyHelper::format($this->taxAmount, $currency) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-base border-t border-slate-800 pt-3 font-bold text-white">
                                <span>Total / Cycle</span>
                                <span class="text-cyan-400">{{ CurrencyHelper::format($this->total, $currency) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Notes & Terms</label>
                        <textarea wire:model="notes" rows="3" placeholder="Specify any additional retainer agreements or notes..." class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" wire:click="toggleCreate" class="px-5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 font-semibold hover:bg-slate-700 transition-all text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold shadow-lg transition-all text-sm">
                            Create Schedule
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
