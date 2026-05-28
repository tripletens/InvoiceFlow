<div>
    @if(!$isFirstTime)
        <div class="mb-6">
            <h2 class="text-2xl font-black text-white">Client Portal</h2>
            <p class="text-slate-400 text-sm mt-1.5">Access your invoices, outstanding balances, and payment histories.</p>
        </div>

        <form wire:submit="handleLogin" class="space-y-5">
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Email Address</label>
                <input wire:model="email" type="email" placeholder="client@example.com"
                    class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            @php
                $hasEmailEntered = !empty($email) && !$errors->has('email');
            @endphp

            @if($hasEmailEntered)
            <div x-transition>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Password</label>
                <input wire:model="password" type="password" placeholder="••••••••"
                    class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @endif

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold text-sm shadow-lg transition-all mt-2">
                Continue to Portal
            </button>
        </form>
    @else
        <div class="mb-6">
            <span class="text-3xl block mb-2">🔒</span>
            <h2 class="text-2xl font-black text-white">Secure Your Portal</h2>
            <p class="text-slate-400 text-sm mt-1.5">It looks like this is your first time logging in. Please create a password to secure your portal.</p>
        </div>

        <form wire:submit="setupPassword" class="space-y-5">
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Email Address</label>
                <input type="email" disabled value="{{ $email }}" class="w-full px-3 py-2.5 rounded-xl bg-slate-950/50 border border-slate-800 text-slate-500 text-sm cursor-not-allowed" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Choose Password *</label>
                <input wire:model="newPassword" type="password" placeholder="At least 6 characters"
                    class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                @error('newPassword') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Confirm Password *</label>
                <input wire:model="confirmNewPassword" type="password" placeholder="Re-enter password"
                    class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                @error('confirmNewPassword') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold text-sm shadow-lg transition-all mt-2">
                Set Password & Log In
            </button>
        </form>
    @endif
</div>
