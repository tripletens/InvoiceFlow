<div>
    <div class="space-y-8">
        
        {{-- Welcome header --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Welcome back, {{ $client->name }} 👋</h1>
                <p class="text-slate-400 text-sm mt-1">Here is your outstanding billing statement and invoice history.</p>
            </div>
            <button wire:click="toggleSettings" class="px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-200 font-semibold text-sm transition-all flex items-center gap-2">
                ⚙️ {{ $isEditingSettings ? 'View Statement' : 'Portal Settings' }}
            </button>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(!$isEditingSettings)
            {{-- KPI Summary & Account details --}}
            <div class="grid lg:grid-cols-12 gap-8 items-start">
                
                {{-- Account Summary card --}}
                <div class="lg:col-span-4 space-y-6">
                    
                    {{-- KPI block --}}
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 space-y-4">
                        <div class="space-y-1">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Outstanding Balance</p>
                            <p class="text-3xl font-black text-rose-400">${{ number_format($outstanding, 2) }}</p>
                        </div>
                        <div class="space-y-1 border-t border-slate-800 pt-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Payments Made</p>
                            <p class="text-3xl font-black text-teal-400">${{ number_format($paid, 2) }}</p>
                        </div>
                        <div class="text-xs text-slate-500 pt-2 leading-relaxed border-t border-slate-800">
                            @if($business && ($business->bank_name || $business->account_number))
                                <p class="font-bold text-slate-400 mb-1">Bank Transfer Details:</p>
                                @if($business->bank_name)<p>Bank: <span class="text-slate-300 font-semibold">{{ $business->bank_name }}</span></p>@endif
                                @if($business->account_name)<p>Account Name: <span class="text-slate-300 font-semibold">{{ $business->account_name }}</span></p>@endif
                                @if($business->account_number)<p>Account Number: <span class="text-slate-300 font-semibold">{{ $business->account_number }}</span></p>@endif
                                @if($business->routing_number)<p>Routing/Sort Code: <span class="text-slate-300 font-semibold">{{ $business->routing_number }}</span></p>@endif
                            @else
                                Please contact your account manager for bank wire details or payments options.
                            @endif
                        </div>
                    </div>

                    {{-- Contact details block --}}
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Account Details</h3>
                        <div class="text-sm space-y-3">
                            <div>
                                <p class="text-xs text-slate-500">Client Name</p>
                                <p class="font-semibold text-slate-300">{{ $client->name }}</p>
                            </div>
                            @if($client->company)
                            <div>
                                <p class="text-xs text-slate-500">Company</p>
                                <p class="font-semibold text-slate-300">{{ $client->company }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-xs text-slate-500">Email Address</p>
                                <p class="font-semibold text-slate-300">{{ $client->email }}</p>
                            </div>
                            @if($client->phone)
                            <div>
                                <p class="text-xs text-slate-500">Phone</p>
                                <p class="font-semibold text-slate-300">{{ $client->phone }}</p>
                            </div>
                            @endif
                            @if($client->address)
                            <div>
                                <p class="text-xs text-slate-500">Billing Address</p>
                                <p class="text-xs text-slate-400 mt-1 max-w-[200px] leading-relaxed">{{ $client->address }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Invoices list --}}
                <div class="lg:col-span-8 rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
                    <div class="p-6 border-b border-slate-800">
                        <h2 class="text-lg font-bold text-slate-200">Invoice Statements</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Download official PDF invoice receipts.</p>
                    </div>

                    @if($invoices->isEmpty())
                        <div class="p-12 text-center text-slate-500">
                            <span class="text-3xl block mb-3">📄</span>
                            No invoices are currently billed to this account.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-300">
                                <thead class="bg-slate-950 text-xs font-bold uppercase tracking-wider text-slate-400">
                                    <tr>
                                        <th class="px-6 py-4">Invoice</th>
                                        <th class="px-6 py-4">Issue Date</th>
                                        <th class="px-6 py-4">Due Date</th>
                                        <th class="px-6 py-4">Amount</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800 bg-slate-900/40">
                                    @foreach($invoices as $invoice)
                                    <tr class="hover:bg-slate-800/30 transition-all">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-white">{{ $invoice->invoice_number }}</div>
                                            <div class="text-[10px] text-slate-500 uppercase">{{ $invoice->currency }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-400 text-xs">
                                            {{ $invoice->issue_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-400 text-xs">
                                            {{ $invoice->due_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-200">
                                            ${{ number_format($invoice->total, 2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $badgeClasses = [
                                                    'draft' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                                                    'sent' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                                    'viewed' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                                    'paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                    'overdue' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $badgeClasses[$invoice->status] ?? 'bg-slate-500/10' }}">
                                                {{ $invoice->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button wire:click="downloadPdf({{ $invoice->id }})" class="px-3 py-1 rounded bg-slate-850 hover:bg-slate-800 text-xs font-semibold text-cyan-400 border border-slate-700/50 hover:text-cyan-300 transition-all">
                                                📥 Download PDF
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- Edit settings form --}}
            <div class="max-w-xl mx-auto rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-white">Portal Passcode Settings</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Secure your client portal access by changing your password.</p>
                </div>

                <form wire:submit="updatePassword" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">New Password</label>
                        <input wire:model="password" type="password" placeholder="At least 6 characters" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Confirm New Password</label>
                        <input wire:model="password_confirmation" type="password" placeholder="Re-enter password" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" wire:click="toggleSettings" class="w-1/2 py-2.5 rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-xs font-bold text-slate-300 transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="w-1/2 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950 font-bold text-xs hover:opacity-90 transition-all shadow-lg shadow-cyan-500/10">
                            Save Password
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
