<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Clients</h1>
                <p class="text-slate-400 text-sm mt-1">Manage your client directory.</p>
            </div>
            <button wire:click="openCreateModal" id="btn-add-client"
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold text-sm shadow-lg transition-all">
                + Add Client
            </button>
        </div>

        {{-- Search --}}
        <div class="flex gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, email or company..."
                class="flex-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-slate-200 text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
        </div>

        {{-- Table --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-slate-500 uppercase tracking-wider border-b border-slate-800">
                            <th class="px-6 py-4 font-semibold">Client</th>
                            <th class="px-6 py-4 font-semibold">Company</th>
                            <th class="px-6 py-4 font-semibold">Phone</th>
                            <th class="px-6 py-4 font-semibold">Invoices</th>
                            <th class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/70">
                        @forelse($clients as $client)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-cyan-400 to-teal-500 flex items-center justify-center text-slate-950 font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-200">{{ $client->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $client->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-400">{{ $client->company ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $client->phone ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                    {{ $client->invoices_count }} invoices
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center gap-2 justify-end">
                                    <button wire:click="openEditModal({{ $client->id }})"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 border border-slate-700 text-slate-300 hover:bg-slate-700 transition-all">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDelete({{ $client->id }})"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 transition-all">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-14 text-slate-500">
                                <p class="text-4xl mb-3">👥</p>
                                <p class="font-medium">No clients yet. Add your first client!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($clients->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">
                {{ $clients->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm" wire:click.self="$set('showModal', false)">
        <div class="w-full max-w-lg mx-4 bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">{{ $editing ? 'Edit Client' : 'Add New Client' }}</h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form wire:submit="save" class="p-6 space-y-4">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Full Name *</label>
                        <input wire:model="name" type="text" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Email *</label>
                        <input wire:model="email" type="email" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Phone</label>
                        <input wire:model="phone" type="text" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Company</label>
                        <input wire:model="company" type="text" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Address</label>
                    <textarea wire:model="address" rows="2" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950 font-bold text-sm hover:opacity-90 transition-all">
                        {{ $editing ? 'Update Client' : 'Create Client' }}
                    </button>
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 text-sm hover:bg-slate-700 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

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
                    For security, please enter your Security PIN to authorize the deletion of this client. This action cannot be undone.
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
