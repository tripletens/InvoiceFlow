<div class="py-20 relative min-h-screen bg-slate-950 selection:bg-cyan-500/30 overflow-hidden" x-data x-on:trigger-paystack.window="
    const data = $event.detail[0] || $event.detail;
    const handler = PaystackPop.setup({
        key: data.key,
        email: data.email,
        amount: data.amount,
        currency: 'NGN',
        callback: function(response) {
            $wire.call('verifyPayment', response.reference, data.planId);
        },
        onClose: function() {
            toastr.error('Payment window closed before completing.');
        }
    });
    handler.openIframe();
">
    {{-- Glow orbs --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $activeSub = auth()->user()->subscriptions()->where('status', 'active')->first();
            $subPlan = $activeSub ? $activeSub->plan_id : null;
            $planLevels = [
                'starter' => 1,
                'pro' => 2,
                'agency' => 3,
            ];
            $effectivePlan = auth()->user()->currentPlan();
            $currentLevel = $planLevels[$effectivePlan] ?? 1;
        @endphp
        
        @if(!auth()->user()->onTrial() && !auth()->user()->hasActiveSubscription() && !auth()->user()->is_admin)
            <div class="mb-12 max-w-4xl mx-auto p-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-center">
                <p class="text-indigo-400 font-bold text-base flex items-center justify-center gap-2">
                    <span>✨</span> Your 14-day free trial has ended!
                </p>
                <p class="text-indigo-400/80 text-sm mt-1">You are currently on the Free Starter plan. Upgrade to Pro or Agency to unlock automated reminders, API access, and custom branding.</p>
            </div>
        @endif

        <div class="text-center mb-14">
            <h1 class="text-3xl sm:text-4xl font-black text-white mb-4">Simple, honest pricing</h1>
            <p class="text-slate-400">No hidden fees. Cancel or downgrade at any time.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            
            {{-- Starter Plan --}}
            <div class="relative rounded-2xl border border-slate-700 bg-slate-900 p-8 flex flex-col">
                <h3 class="text-xl font-black text-white">Starter</h3>
                <div class="mt-3 mb-1">
                    <span class="text-4xl font-black text-white">$0</span>
                    <span class="text-slate-400 text-sm">/mo</span>
                </div>
                <p class="text-slate-400 text-sm mb-6">For freelancers just getting started.</p>
                
                <ul class="space-y-2 flex-1 mb-8">
                    @foreach([
                        'Up to 3 invoices/mo',
                        '1 Business profile',
                        'Client directory',
                        'PDF downloads'
                    ] as $feature)
                    <li class="flex items-center gap-2 text-sm text-slate-300">
                        <span class="text-teal-400">✓</span> {{ $feature }}
                    </li>
                    @endforeach
                </ul>

                @if($subPlan === 'starter' || ($subPlan === null && !auth()->user()->onTrial() && !auth()->user()->is_admin))
                    <button disabled class="w-full text-center py-3 rounded-xl bg-slate-800 border border-slate-700 text-slate-500 font-semibold text-sm cursor-not-allowed">Current Plan</button>
                @else
                    <button wire:click="confirmSubscription('starter')" class="w-full text-center py-3 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 hover:opacity-90 font-semibold text-sm transition-all">
                        {{ $currentLevel > 1 ? 'Downgrade to Starter' : 'Subscribe to Starter' }}
                    </button>
                @endif
            </div>

            {{-- Pro Plan --}}
            <div class="relative rounded-2xl border border-cyan-500 bg-gradient-to-b from-cyan-500/10 to-teal-500/5 p-8 flex flex-col">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950">Most Popular</span>
                
                <h3 class="text-xl font-black text-white">Pro</h3>
                <div class="mt-3 mb-1">
                    <span class="text-4xl font-black text-white">$19</span>
                    <span class="text-slate-400 text-sm">/mo</span>
                </div>
                <p class="text-slate-400 text-sm mb-6">For growing agencies and founders.</p>
                
                <ul class="space-y-2 flex-1 mb-8">
                    @foreach([
                        'Unlimited invoices',
                        '2 Business profiles',
                        'Developer API access',
                        'Automated reminders',
                        'Priority support'
                    ] as $feature)
                    <li class="flex items-center gap-2 text-sm text-slate-300">
                        <span class="text-teal-400">✓</span> {{ $feature }}
                    </li>
                    @endforeach
                </ul>

                @if($subPlan === 'pro')
                    <button disabled class="w-full text-center py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950 font-bold shadow-lg opacity-50 cursor-not-allowed">Current Plan</button>
                @else
                    <button wire:click="confirmSubscription('pro')" class="{{ ($subPlan && $currentLevel > 2) ? 'w-full text-center py-3 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 hover:opacity-90 font-semibold text-sm transition-all' : 'w-full text-center py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950 font-bold shadow-lg hover:opacity-90 transition-all' }}">
                        {{ $subPlan ? ($currentLevel > 2 ? 'Downgrade to Pro' : 'Upgrade to Pro') : ($currentLevel > 2 ? 'Subscribe to Pro' : 'Upgrade to Pro') }}
                    </button>
                @endif
            </div>

            {{-- Agency Plan --}}
            <div class="relative rounded-2xl border border-slate-700 bg-slate-900 p-8 flex flex-col">
                <h3 class="text-xl font-black text-white">Agency</h3>
                <div class="mt-3 mb-1">
                    <span class="text-4xl font-black text-white">$59</span>
                    <span class="text-slate-400 text-sm">/mo</span>
                </div>
                <p class="text-slate-400 text-sm mb-6">For large teams and enterprises.</p>
                
                <ul class="space-y-2 flex-1 mb-8">
                    @foreach([
                        'Unlimited everything',
                        'Unlimited Business profiles',
                        'Full API access',
                        'Custom branding',
                        'Dedicated support'
                    ] as $feature)
                    <li class="flex items-center gap-2 text-sm text-slate-300">
                        <span class="text-teal-400">✓</span> {{ $feature }}
                    </li>
                    @endforeach
                </ul>

                @if($subPlan === 'agency')
                    <button disabled class="w-full text-center py-3 rounded-xl bg-slate-800 border border-slate-700 text-slate-500 font-semibold text-sm cursor-not-allowed">Current Plan</button>
                @else
                    <button wire:click="confirmSubscription('agency')" class="w-full text-center py-3 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 hover:opacity-90 font-semibold text-sm transition-all">
                        {{ $subPlan ? 'Upgrade to Agency' : ($currentLevel == 3 ? 'Subscribe to Agency' : 'Upgrade to Agency') }}
                    </button>
                @endif
            </div>

        </div>

        {{-- Plan & Billing History --}}
        <div class="mt-16 rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden shadow-2xl">
            <div class="p-6 pr-8 sm:pr-10 lg:pr-12 border-b border-slate-800 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h2 class="text-lg font-bold text-slate-100 flex items-center gap-2">
                        <span>💳</span> Plan & Billing History
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">Track your subscription tier changes, upgrades, and billing events.</p>
                </div>
            </div>

            @if($subscriptions->isEmpty())
                <div class="p-12 text-center text-slate-500">
                    <span class="text-3xl block mb-3 opacity-50">💳</span>
                    No billing history records found. Your current plan is Basic/Trial.
                </div>
            @else
                <div class="divide-y divide-slate-800 bg-slate-950/20">
                    @foreach($subscriptions as $sub)
                    <div class="p-6 pr-8 sm:pr-10 lg:pr-12 flex flex-col sm:flex-row sm:items-center justify-between gap-6 hover:bg-slate-800/10 transition-all duration-150">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-center text-xl shadow-inner shrink-0">
                                💼
                            </div>
                            <div>
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="font-bold text-white uppercase text-sm tracking-wide">
                                        {{ $sub->plan_id }} Plan
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-slate-800 border border-slate-700 text-slate-400 shadow-sm capitalize">
                                        {{ $sub->gateway }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-1 font-mono tracking-tight">
                                    Transaction ID: {{ $sub->gateway_subscription_id }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:items-end gap-2 text-xs text-slate-300 shrink-0">
                            <div class="flex items-center gap-2 font-medium">
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Period:</span>
                                <span class="bg-slate-800/50 border border-slate-800/80 px-2 py-0.5 rounded text-[11px] text-slate-400">
                                    {{ $sub->created_at->format('M d, Y') }} - {{ $sub->current_period_end ? $sub->current_period_end->format('M d, Y') : 'Present' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($sub->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-extrabold uppercase tracking-wider shadow-sm shadow-emerald-500/5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800 border border-slate-700/50 text-slate-400 text-[10px] font-extrabold uppercase tracking-wider">
                                        Canceled / Expired
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- Subscription Confirmation PIN Modal --}}
    @if($confirmingSubscription)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm" wire:click.self="cancelSubscriptionConfirm">
        <div class="w-full max-w-md mx-4 bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">Confirm Plan Change</h2>
                <button wire:click="cancelSubscriptionConfirm" class="text-slate-400 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form wire:submit.prevent="verifyAndSubscribe" class="p-6 space-y-4">
                <div class="text-sm text-slate-300">
                    For security, please enter your Security PIN to authorize this change to your subscription plan.
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Security PIN</label>
                    <input wire:model="confirmPinInput" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" autofocus
                        class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/40" placeholder="••••" />
                    @error('confirmPinInput') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-bold text-sm transition-all shadow-lg shadow-teal-600/10">
                        Verify & Confirm
                    </button>
                    <button type="button" wire:click="cancelSubscriptionConfirm" class="px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 text-sm hover:bg-slate-700 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
