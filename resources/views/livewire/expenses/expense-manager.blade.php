@php use App\Helpers\CurrencyHelper; @endphp
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Header --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Expense Tracking 💸</h1>
                <p class="text-slate-400 text-sm mt-1">Monitor company expenditures, categorizations, and save digital receipts.</p>
            </div>
            <button wire:click="toggleCreate" class="px-4 py-2 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 hover:opacity-90 text-white font-bold text-sm shadow-lg transition-all">
                {{ $isCreating ? '← Back to List' : '+ Record Expense' }}
            </button>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(!$isCreating)
            {{-- Metrics and Category Breakdowns --}}
            <div class="grid lg:grid-cols-12 gap-8 items-start">
                
                {{-- Overall Spend --}}
                <div class="lg:col-span-4 rounded-2xl border border-slate-800 bg-slate-900/50 p-6 space-y-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Business Expenses</p>
                    <p class="text-4xl font-black text-red-400">{{ CurrencyHelper::format($totalExpenses, auth()->user()->default_currency ?? 'USD') }}</p>
                    
                    <div class="border-t border-slate-800 pt-4 space-y-2">
                        <p class="text-xs text-slate-400 font-semibold">Expense Categories Distribution</p>
                        @if(empty($categoriesData))
                            <p class="text-xs text-slate-500">No categorizations recorded yet.</p>
                        @else
                            <div class="space-y-3 mt-2">
                                @foreach($categoriesData as $cat => $total)
                                    @php
                                        $percent = $totalExpenses > 0 ? ($total / $totalExpenses) * 100 : 0;
                                        $barColor = match($cat) {
                                            'Software' => 'bg-cyan-500',
                                            'Travel' => 'bg-indigo-500',
                                            'Marketing' => 'bg-pink-500',
                                            'Office' => 'bg-amber-500',
                                            'Salaries' => 'bg-emerald-500',
                                            default => 'bg-slate-400'
                                        };
                                    @endphp
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-xs font-semibold text-slate-300">
                                            <span>{{ $cat }}</span>
                                            <span>{{ CurrencyHelper::format($total, auth()->user()->default_currency ?? 'USD') }} ({{ round($percent) }}%)</span>
                                        </div>
                                        <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                            <div class="{{ $barColor }} h-1.5" style="width: {{ $percent }}%;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Spend list ledger --}}
                <div class="lg:col-span-8 space-y-4">
                    
                    {{-- Search & Filters --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <div class="flex-1 min-w-[200px]">
                            <input wire:model.live="search" type="text" placeholder="Search expenses..." class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-200 text-sm focus:outline-none focus:ring-1 focus:ring-red-500" />
                        </div>
                        <div>
                            <select wire:model.live="filterCategory" class="px-3 py-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-300 text-sm focus:outline-none focus:ring-1 focus:ring-red-500">
                                <option value="">All Categories</option>
                                @foreach($availableCategories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Expenses Table --}}
                    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
                        @if($expenses->isEmpty())
                            <div class="p-12 text-center text-slate-500">
                                <span class="text-3xl block mb-3">💸</span>
                                No business expenses matching search criteria. Click "Record Expense" to register one.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-300">
                                    <thead class="bg-slate-950 text-xs font-bold uppercase tracking-wider text-slate-400">
                                        <tr>
                                            <th class="px-6 py-4">Expense Title</th>
                                            <th class="px-6 py-4">Category</th>
                                            <th class="px-6 py-4">Date</th>
                                            <th class="px-6 py-4">Amount</th>
                                            <th class="px-6 py-4">Receipt</th>
                                            <th class="px-6 py-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800 bg-slate-900/40">
                                        @foreach($expenses as $exp)
                                        <tr class="hover:bg-slate-800/30 transition-all">
                                            <td class="px-6 py-4">
                                                <div class="font-semibold text-white">{{ $exp->title }}</div>
                                                <div class="text-xs text-slate-400 max-w-xs truncate" title="{{ $exp->notes }}">{{ $exp->notes ?? 'No description' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $catColors = [
                                                        'Software' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                                        'Travel' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                                        'Marketing' => 'bg-pink-500/10 text-pink-400 border-pink-500/20',
                                                        'Office' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                        'Salaries' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-0.5 rounded text-xs border font-semibold {{ $catColors[$exp->category] ?? 'bg-slate-500/10 text-slate-400 border-slate-500/20' }}">
                                                    {{ $exp->category }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-400 text-xs">
                                                {{ $exp->expense_date->format('Y-m-d') }}
                                            </td>
                                            <td class="px-6 py-4 font-bold text-red-400">
                                                -{{ CurrencyHelper::format($exp->amount, $exp->currency) }}
                                            </td>
                                            <td class="px-6 py-4 text-xs">
                                                @if($exp->receipt_path)
                                                    <a href="{{ Storage::url($exp->receipt_path) }}" target="_blank" class="text-cyan-400 hover:text-cyan-300 font-semibold underline flex items-center gap-1">
                                                        📄 View Receipt
                                                    </a>
                                                @else
                                                    <span class="text-slate-500 italic">None</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <button wire:click="delete({{ $exp->id }})" class="p-1 rounded bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-all text-xs">
                                                    🗑️ Delete
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-6 border-t border-slate-800">
                                {{ $expenses->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            {{-- Create Expense Form --}}
            <div class="max-w-2xl mx-auto space-y-6">
                <form wire:submit="save" class="space-y-6">
                    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
                        <h2 class="text-base font-bold text-slate-200 border-b border-slate-800 pb-3">Record Expenditure</h2>
                        
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Title / Vendor *</label>
                                <input type="text" wire:model="title" placeholder="e.g. AWS Cloud Services" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/40" />
                                @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Amount *</label>
                                <input type="number" step="0.01" min="0.01" wire:model="amount" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/40" />
                                @error('amount') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Currency</label>
                                <select wire:model="currency" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/40">
                                    <option value="USD">USD — Dollar</option>
                                    <option value="EUR">EUR — Euro</option>
                                    <option value="GBP">GBP — Pound</option>
                                    <option value="NGN">NGN — Naira</option>
                                    <option value="CAD">CAD — Canadian Dollar</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Category *</label>
                                <div class="flex gap-2 items-center">
                                    <select wire:model="category" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/40">
                                        @foreach($availableCategories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" wire:click="toggleAddCategory" class="px-3.5 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-300 text-sm hover:bg-slate-700 transition-all" title="Add Custom Category">
                                        ➕
                                    </button>
                                </div>
                                @error('category') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Expense Date *</label>
                                <input type="date" wire:model="expense_date" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/40" />
                                @error('expense_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            @if($isAddingCategory)
                            <div class="sm:col-span-2 p-4 rounded-xl border border-slate-800 bg-slate-950/40 space-y-3" x-transition>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">New Custom Category</span>
                                    <button type="button" wire:click="toggleAddCategory" class="text-slate-500 hover:text-slate-300 text-xs">Close</button>
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" wire:model="newCategoryName" placeholder="e.g. Subcontractors" class="w-full px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-1 focus:ring-red-500" />
                                    <button type="button" wire:click="saveCategory" class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition-all">
                                        Add
                                    </button>
                                </div>
                                @error('newCategoryName') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                @if (session()->has('category_success'))
                                    <p class="text-emerald-400 text-xs mt-1">{{ session('category_success') }}</p>
                                @endif
                            </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Scan / Upload Receipt</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-800 border-dashed rounded-xl hover:border-red-500/40 transition-all bg-slate-800/20">
                                <div class="space-y-1 text-center">
                                    <span class="text-3xl block">📷</span>
                                    <div class="flex text-sm text-slate-400">
                                        <label class="relative cursor-pointer rounded-md font-bold text-cyan-400 hover:text-cyan-300">
                                            <span>Upload a file</span>
                                            <input type="file" wire:model="receipt" class="sr-only" accept="image/*,application/pdf" />
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-[10px] text-slate-500">PNG, JPG, PDF up to 5MB</p>
                                </div>
                            </div>
                            @if ($receipt)
                                <div class="mt-3 p-2 bg-slate-800/80 rounded-lg text-xs text-emerald-400 flex items-center gap-2">
                                    <span>✓</span> {{ $receipt->getClientOriginalName() }} uploaded!
                                </div>
                            @endif
                            @error('receipt') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Additional Notes</label>
                            <textarea wire:model="notes" rows="3" placeholder="Vendor invoice details, projects associated..." class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/40"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" wire:click="toggleCreate" class="px-5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 font-semibold hover:bg-slate-700 transition-all text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 hover:opacity-90 text-white font-bold shadow-lg transition-all text-sm">
                            Save Expense Record
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</div>
