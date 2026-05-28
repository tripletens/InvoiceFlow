<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-white">Automated Reminders</h1>
            <p class="text-slate-400 text-sm mt-1">Configure automatic payment reminders sent to your clients.</p>
        </div>

        @if($plan === 'starter')
        {{-- Starter Paywall --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-12 text-center">
            <div class="text-5xl mb-4">⏰</div>
            <h2 class="text-xl font-bold text-white mb-2">Automated Reminders Require Pro or Higher</h2>
            <p class="text-slate-400 mb-2">Stop chasing payments manually. Let InvoiceFlow automatically email your clients before and after due dates.</p>
            <ul class="text-sm text-slate-500 mb-6 space-y-1">
                <li>📬 3 days before due date</li>
                <li>📬 1 day before due date</li>
                <li>📬 On the due date</li>
                <li>📬 Every 7 days after overdue</li>
            </ul>
            <a href="{{ route('upgrade') }}" wire:navigate class="inline-block px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-black shadow-lg transition-all">
                ⭐ Upgrade to Pro to Unlock Reminders
            </a>
        </div>
        @else

        {{-- Reminder Triggers --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-8 space-y-6">
            <h2 class="text-lg font-bold text-slate-100">Reminder Schedule</h2>
            <p class="text-slate-400 text-sm">Choose which automatic email reminders to send to clients about their invoices.</p>

            <div class="space-y-4">
                @php
                    $reminders = [
                        ['wire' => 'reminder3days', 'label' => '3 days before due date', 'desc' => 'A friendly heads-up before the invoice is due.', 'icon' => '📅'],
                        ['wire' => 'reminder1day',  'label' => '1 day before due date',  'desc' => 'A final nudge the day before the deadline.',    'icon' => '⏳'],
                        ['wire' => 'reminderOnDue', 'label' => 'On the due date',         'desc' => 'An immediate reminder on the day payment is due.', 'icon' => '📌'],
                        ['wire' => 'reminderOverdue','label' => 'When invoice is overdue', 'desc' => 'A firm reminder once the deadline has passed.',  'icon' => '🚨'],
                    ];
                @endphp

                @foreach($reminders as $r)
                <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-800/50 border border-slate-700/50 hover:border-slate-700 transition-all">
                    <div class="text-2xl shrink-0 mt-0.5">{{ $r['icon'] }}</div>
                    <div class="flex-1">
                        <p class="font-semibold text-slate-200 text-sm">{{ $r['label'] }}</p>
                        <p class="text-slate-500 text-xs mt-0.5">{{ $r['desc'] }}</p>
                    </div>
                    <div class="shrink-0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input wire:model="{{ $r['wire'] }}" type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500"></div>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Overdue interval --}}
            @if($reminderOverdue)
            <div class="p-4 rounded-xl bg-red-500/5 border border-red-500/20">
                <label class="block text-sm font-semibold text-slate-300 mb-2">Repeat overdue reminders every:</label>
                <div class="flex items-center gap-3">
                    <input wire:model="overdueIntervalDays" type="number" min="1" max="30"
                        class="w-20 px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-slate-200 text-sm text-center focus:outline-none focus:ring-2 focus:ring-red-500/40" />
                    <span class="text-slate-400 text-sm">days</span>
                </div>
            </div>
            @endif

            <button wire:click="save" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold shadow-lg transition-all">
                Save Preferences
            </button>
        </div>

        {{-- How it works --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-3">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">How It Works</h2>
            <p class="text-slate-400 text-sm">Once you mark an invoice as "Sent", InvoiceFlow will automatically email your client the configured reminders based on the invoice's due date. No manual follow-up needed!</p>
        </div>
        @endif

    </div>
</div>
