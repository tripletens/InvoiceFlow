<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Business Profiles</h1>
                <p class="text-slate-400 text-sm mt-1">Manage the profiles you use to send invoices.</p>
            </div>
            
            @if(auth()->user()->canCreateBusiness())
                <button wire:click="openCreate" class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold text-sm shadow-lg transition-all">
                    + Add Business Profile
                </button>
            @else
                <a href="{{ route('upgrade') }}" wire:navigate class="px-4 py-2 rounded-xl bg-gradient-to-r from-violet-500 to-indigo-500 hover:opacity-90 text-white font-bold text-sm shadow-lg transition-all flex items-center gap-2">
                    <span>⭐</span> Upgrade to Add More Profiles
                </a>
            @endif
        </div>

        {{-- Profile List --}}
        <div class="grid md:grid-cols-2 gap-6">
            @forelse($businesses as $business)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-slate-700 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-cyan-500/20 to-teal-500/20 border border-cyan-500/30 flex items-center justify-center shrink-0">
                                <span class="text-cyan-400 font-bold text-lg">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white leading-snug">{{ $business->name }}</h3>
                                @if($business->email) <p class="text-sm text-slate-400 mt-0.5">{{ $business->email }}</p> @endif
                                @if($business->phone) <p class="text-sm text-slate-400 mt-0.5">{{ $business->phone }}</p> @endif
                            </div>
                        </div>

                        {{-- Edit/Delete Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <button wire:click="openEdit({{ $business->id }})" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all text-xs font-semibold" title="Edit Profile">
                                ✏️
                            </button>
                            <button wire:confirm="Are you sure you want to delete this business profile?" wire:click="deleteBusiness({{ $business->id }})" class="p-1.5 rounded-lg bg-red-500/10 border border-red-500/20 hover:bg-red-500/20 text-red-400 transition-all text-xs font-semibold" title="Delete Profile">
                                🗑️
                            </button>
                        </div>
                    </div>
                    @if($business->address)
                    <div class="mt-4 pt-4 border-t border-slate-800/60">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Address</p>
                        <p class="text-sm text-slate-400 whitespace-pre-wrap">{{ $business->address }}</p>
                    </div>
                    @endif
                    @if($business->bank_name || $business->account_number)
                    <div class="mt-4 pt-4 border-t border-slate-800/60">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Bank Details</p>
                        <p class="text-sm text-slate-400">{{ $business->bank_name }} &middot; Acct: {{ $business->account_number }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center border border-dashed border-slate-800 rounded-2xl">
                <p class="text-4xl mb-3">🏢</p>
                <p class="text-slate-400 font-medium">You haven't added any business profiles yet.</p>
            </div>
            @endforelse
        </div>

        {{-- Create Modal --}}
        @if($isCreating)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg shadow-2xl shadow-slate-950 overflow-hidden">
                <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">{{ $editing ? 'Edit Business Profile' : 'Add Business Profile' }}</h2>
                    <button wire:click="cancelCreate" class="text-slate-500 hover:text-white transition-colors">&times;</button>
                </div>
                <div class="p-6 space-y-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Business Name <span class="text-red-400">*</span></label>
                        <input wire:model="name" type="text" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                            <input wire:model="email" type="email" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                            @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Phone</label>
                            <input wire:model="phone" type="text" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                            @error('phone') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Address</label>
                        <textarea wire:model="address" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/40"></textarea>
                        @error('address') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-800">
                        <h3 class="text-sm font-bold text-slate-300 mb-3">Bank Transfer Details</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Bank Name</label>
                                <input wire:model="bank_name" type="text" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/40" placeholder="e.g. Chase Bank" />
                                @error('bank_name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Account Name</label>
                                <input wire:model="account_name" type="text" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                                @error('account_name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Account Number</label>
                                <input wire:model="account_number" type="text" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                                @error('account_number') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Routing Number / Sort Code</label>
                                <input wire:model="routing_number" type="text" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                                @error('routing_number') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <p class="text-xs text-slate-500">These details will be displayed on your invoices so clients know where to send payments.</p>
                    </div>

                </div>
                <div class="p-6 bg-slate-950 border-t border-slate-800 flex justify-end gap-3">
                    <button wire:click="cancelCreate" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold transition-colors">Cancel</button>
                    <button wire:click="save" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold shadow-lg transition-all flex items-center gap-2">
                        <span wire:loading wire:target="save" class="animate-spin">⏳</span> {{ $editing ? 'Update Profile' : 'Save Profile' }}
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
