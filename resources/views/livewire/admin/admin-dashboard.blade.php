<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2"><span class="text-violet-400">👑</span> Admin Dashboard</h1>
            <p class="text-slate-400 text-sm mt-1">Platform-wide metrics and user management.</p>
        </div>

        {{-- Stats --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-5">
            @foreach([
                ['label'=>'Total Users','value'=>$stats['total_users'],'icon'=>'👥','color'=>'border-cyan-500/20 bg-cyan-500/5'],
                ['label'=>'Admins','value'=>$stats['total_admins'],'icon'=>'👑','color'=>'border-violet-500/20 bg-violet-500/5'],
                ['label'=>'Total Invoices','value'=>$stats['total_invoices'],'icon'=>'🧾','color'=>'border-blue-500/20 bg-blue-500/5'],
                ['label'=>'Paid Invoices','value'=>$stats['paid_invoices'],'icon'=>'✅','color'=>'border-teal-500/20 bg-teal-500/5'],
                ['label'=>'Platform Revenue','value'=>'$'.number_format($stats['total_revenue'],2),'icon'=>'💰','color'=>'border-amber-500/20 bg-amber-500/5'],
            ] as $card)
            <div class="rounded-2xl border {{ $card['color'] }} p-5 flex items-center gap-4">
                <span class="text-2xl">{{ $card['icon'] }}</span>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide">{{ $card['label'] }}</p>
                    <p class="text-xl font-black text-white">{{ $card['value'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Payment Settings --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2">💳 Payment Gateway configuration</h2>
                <p class="text-sm text-slate-400 mt-1">Select which gateway businesses will use to pay for their subscriptions.</p>

            </div>
            <div>
                <select wire:model.live="activeGateway" class="px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-violet-500/40">
                    <option value="stripe">Stripe (International)</option>
                    <option value="paystack">Paystack (Africa)</option>
                </select>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
            <div class="p-5 border-b border-slate-800 flex flex-col sm:flex-row gap-4 sm:items-center justify-between">
                <h2 class="text-base font-bold text-slate-100">All Users</h2>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..."
                    class="px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/40 w-full sm:w-72" />
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-slate-500 uppercase tracking-wider border-b border-slate-800">
                            <th class="px-6 py-4 font-semibold">User</th>
                            <th class="px-6 py-4 font-semibold">Role</th>
                            <th class="px-6 py-4 font-semibold">Invoices</th>
                            <th class="px-6 py-4 font-semibold">Joined</th>
                            <th class="px-6 py-4 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/70">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-cyan-400 to-violet-500 flex items-center justify-center text-slate-950 font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($user->name,0,1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-200">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold border {{ $user->is_admin ? 'bg-violet-500/20 text-violet-300 border-violet-500/30' : 'bg-slate-700 text-slate-300 border-slate-600/30' }}">
                                    {{ $user->is_admin ? '👑 Admin' : '🧑 User' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-300">{{ $user->invoices_count }}</td>
                            <td class="px-6 py-4 text-slate-500 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                @if($user->id !== auth()->id())
                                <button wire:click="toggleSuspend({{ $user->id }})"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all {{ $user->is_admin ? 'bg-violet-500/10 border-violet-500/30 text-violet-400 hover:bg-violet-500/20' : 'bg-slate-800 border-slate-700 text-slate-300 hover:bg-slate-700' }}">
                                    {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                </button>
                                @else
                                <span class="text-xs text-slate-600 italic">You</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-12 text-slate-500">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
</div>
