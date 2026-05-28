<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Invoices</h1>
                <p class="text-slate-400 text-sm mt-1">Track and manage all your invoices.</p>
            </div>
            
            @if(auth()->user()->canCreateInvoice())
                <a href="{{ route('invoices.create') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold text-sm shadow-lg transition-all">
                    + New Invoice
                </a>
            @else
                <a href="{{ route('upgrade') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-violet-500 to-indigo-500 hover:opacity-90 text-white font-bold text-sm shadow-lg transition-all flex items-center gap-2">
                    <span>⭐</span> Upgrade to Create More
                </a>
            @endif
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-1 flex-wrap gap-3 min-w-[300px]">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by client or invoice #..."
                    class="flex-1 min-w-48 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-slate-200 text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                <select wire:model.live="filterStatus" class="px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40">
                    <option value="">All Statuses</option>
                    <option value="draft">📝 Draft</option>
                    <option value="sent">📤 Sent</option>
                    <option value="viewed">👁️ Viewed</option>
                    <option value="paid">✅ Paid</option>
                    <option value="overdue">⚠️ Overdue</option>
                </select>
            </div>
            
            <div class="flex gap-2">
                <button wire:click="exportCsv" 
                    class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 font-semibold text-sm transition-all flex items-center gap-2 shadow-md">
                    📥 CSV
                </button>
                <button wire:click="exportExcel" 
                    class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 font-semibold text-sm transition-all flex items-center gap-2 shadow-md">
                    📊 Excel
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-slate-500 uppercase tracking-wider border-b border-slate-800">
                            <th class="px-6 py-4 font-semibold">Invoice #</th>
                            <th class="px-6 py-4 font-semibold">Client</th>
                            <th class="px-6 py-4 font-semibold">Total</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Due Date</th>
                            <th class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/70">
                        @forelse($invoices as $invoice)
                        @php
                            $statusColors = [
                                'draft'   => 'bg-slate-700/60 text-slate-300 border-slate-600/50',
                                'sent'    => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
                                'viewed'  => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
                                'paid'    => 'bg-teal-500/10 text-teal-400 border-teal-500/30',
                                'overdue' => 'bg-red-500/10 text-red-400 border-red-500/30',
                            ];
                            $statusLabels = ['draft'=>'📝 Draft','sent'=>'📤 Sent','viewed'=>'👁️ Viewed','paid'=>'✅ Paid','overdue'=>'⚠️ Overdue'];
                        @endphp
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 font-mono font-semibold text-cyan-400">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="hover:underline">{{ $invoice->invoice_number }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-200">{{ $invoice->client->name }}</p>
                                <p class="text-xs text-slate-500">{{ $invoice->client->company ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4 font-bold text-white">${{ number_format($invoice->total, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$invoice->status] ?? '' }}">
                                    {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">{{ $invoice->due_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center gap-1.5 justify-end flex-wrap">
                                    <a href="{{ route('invoices.show', $invoice->id) }}"
                                        class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-700/50 border border-slate-600 text-slate-300 hover:bg-slate-700 transition-all">
                                        View
                                    </a>
                                    <button wire:click="downloadPdf({{ $invoice->id }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-800 border border-slate-700 text-slate-300 hover:bg-slate-700 transition-all">
                                        PDF
                                    </button>
                                    @if($invoice->status !== 'paid')
                                    <button wire:click="updateStatus({{ $invoice->id }}, 'paid')"
                                        class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-500/10 border border-teal-500/30 text-teal-400 hover:bg-teal-500/20 transition-all">
                                        Mark Paid
                                    </button>
                                    @endif
                                    @if($invoice->status === 'draft')
                                    <button wire:click="updateStatus({{ $invoice->id }}, 'sent')"
                                        class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-500/10 border border-blue-500/30 text-blue-400 hover:bg-blue-500/20 transition-all">
                                        Mark Sent
                                    </button>
                                    @endif
                                    <button wire:click="confirmDelete({{ $invoice->id }})"
                                         class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 transition-all">
                                         Delete
                                     </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-14 text-slate-500">
                                <p class="text-4xl mb-3">🧾</p>
                                <p class="font-medium">No invoices found.</p>
                                <a href="{{ route('invoices.create') }}" class="mt-3 inline-block text-cyan-400 hover:text-cyan-300 text-sm font-semibold">Create your first invoice →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">
                {{ $invoices->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation PIN Modal --}}
    @if($confirmingDelete)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm" wire:click.self="cancelDelete">
        <div class="w-full max-w-md mx-4 bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">Confirm Deletion</h2>
                <button wire:click="cancelDelete" class="text-slate-400 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form wire:submit.prevent="verifyAndPerformDelete" class="p-6 space-y-4">
                <div class="text-sm text-slate-300">
                    For security, please enter your Security PIN to authorize the deletion of this invoice. This action cannot be undone.
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Security PIN</label>
                    <input wire:model="confirmPinInput" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" autofocus
                        class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" placeholder="••••" />
                    @error('confirmPinInput') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-sm transition-all shadow-lg shadow-red-600/10">
                        Verify & Delete
                    </button>
                    <button type="button" wire:click="cancelDelete" class="px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 text-sm hover:bg-slate-700 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
