<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-white">Profile Settings</h1>
            <p class="text-slate-400 text-sm mt-1">Manage your account information and configure security controls.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">

            {{-- Account Information Form --}}
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-white">Account Details</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Update your contact profile and password.</p>
                </div>

                <form wire:submit="updateProfile" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Full Name</label>
                        <input wire:model="name" type="text" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Email Address</label>
                        <input wire:model="email" type="email" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Default Currency</label>
                        <select wire:model="default_currency" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40">
                            <option value="USD">USD — US Dollar</option>
                            <option value="EUR">EUR — Euro</option>
                            <option value="GBP">GBP — British Pound</option>
                            <option value="NGN">NGN — Nigerian Naira</option>
                            <option value="CAD">CAD — Canadian Dollar</option>
                        </select>
                        @error('default_currency') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="border-t border-slate-800/80 pt-4">
                        <p class="text-xs text-slate-500 mb-4">Leave fields empty if you don't wish to change the password.</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">New Password</label>
                                <input wire:model="password" type="password" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Confirm New Password</label>
                                <input wire:model="password_confirmation" type="password" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950 font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-cyan-500/10">
                            Save Account Details
                        </button>
                    </div>
                </form>
            </div>

            {{-- Security PIN Form --}}
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-white">Security Authorization PIN</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Authorized operations (like delete) will require this PIN.</p>
                </div>

                @if($hasPin)
                    <div class="rounded-xl border border-teal-500/20 bg-teal-500/5 p-4 flex items-start gap-3">
                        <span class="text-xl">🛡️</span>
                        <div>
                            <p class="text-xs font-bold text-teal-400 uppercase tracking-wider">PIN Verification Active</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Critical operations will now ask for your numeric authorization PIN.</p>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-4 flex items-start gap-3">
                        <span class="text-xl">⚠️</span>
                        <div>
                            <p class="text-xs font-bold text-amber-400 uppercase tracking-wider">PIN Verification Off</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Please set up a 4 to 6 digit numeric security PIN to enable critical actions.</p>
                        </div>
                    </div>
                @endif

                <form wire:submit="updatePin" class="space-y-4">
                    @if($hasPin)
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Current Security PIN</label>
                            <input wire:model="old_pin" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" placeholder="••••" />
                            @error('old_pin') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">{{ $hasPin ? 'New Security PIN' : 'Create Security PIN' }}</label>
                        <input wire:model="pin" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" placeholder="••••" />
                        @error('pin') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Confirm Security PIN</label>
                        <input wire:model="pin_confirmation" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" placeholder="••••" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950 font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-cyan-500/10">
                            {{ $hasPin ? 'Update Security PIN' : 'Enable Security PIN' }}
                        </button>
                    </div>
                </form>
            </div>

        </div>

        {{-- Subscription Plan Information --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-white">Subscription Plan</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Manage your current subscription and billing.</p>
                </div>
                <div>
                    <a href="{{ route('upgrade') }}" wire:navigate class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-cyan-500/20 transition-all">
                        {{ auth()->user()->currentPlan() === 'basic' ? 'Upgrade Plan' : 'Manage Subscription' }}
                    </a>
                </div>
            </div>
            <div class="rounded-xl border border-slate-800/80 bg-slate-800/30 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Current Plan</p>
                    <p class="text-xl font-bold text-white mt-1 capitalize">{{ auth()->user()->currentPlan() }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">
                        Active
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
