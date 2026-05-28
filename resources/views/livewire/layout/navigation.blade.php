<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="sticky top-0 z-50 border-b border-slate-800 bg-slate-950/90 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-tr from-cyan-400 to-teal-500 flex items-center justify-center shadow-lg">
                    <span class="font-black text-slate-950 text-sm">IF</span>
                </div>
                <span class="font-bold text-lg text-white tracking-tight">InvoiceFlow</span>
            </a>

            {{-- Main Nav --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                    Dashboard
                </a>
                <a href="{{ route('clients.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('clients.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                    Clients
                </a>
                <a href="{{ route('invoices.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('invoices.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                    Invoices
                </a>
                <a href="{{ route('recurring-invoices.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('recurring-invoices.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                    🔄 Recurring
                </a>
                <a href="{{ route('expenses.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('expenses.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                    💸 Expenses
                </a>
                <a href="{{ route('invoices.create') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-cyan-400 hover:text-cyan-300 hover:bg-slate-800/60 transition-all">
                    + New Invoice
                </a>

                {{-- Settings Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('settings.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all flex items-center gap-1">
                        Settings
                        <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute left-0 mt-2 w-52 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl shadow-slate-950/60 overflow-hidden z-50">
                        <a href="{{ route('settings.businesses') }}" class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('settings.businesses') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                            🏢 <span>Businesses</span>
                        </a>
                        <a href="{{ route('settings.categories') }}" class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('settings.categories') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                            🏷️ <span>Expense Categories</span>
                        </a>
                        <a href="{{ route('settings.reminders') }}" class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('settings.reminders') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                            ⏰ <span>Reminders</span>
                            @if(auth()->user()?->currentPlan() === 'starter')
                            <span class="ml-auto text-xs font-bold text-violet-400">Pro</span>
                            @endif
                        </a>
                        <a href="{{ route('settings.branding') }}" class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('settings.branding') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                            🎨 <span>Branding</span>
                            @if(auth()->user()?->currentPlan() !== 'agency')
                            <span class="ml-auto text-xs font-bold text-amber-400">Agency</span>
                            @endif
                        </a>
                        <a href="{{ route('settings.designer') }}" class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('settings.designer') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                            🖌️ <span>Invoice Designer</span>
                            @if(auth()->user()?->currentPlan() === 'starter')
                            <span class="ml-auto text-xs font-bold text-violet-400">Pro</span>
                            @endif
                        </a>
                        <div class="border-t border-slate-800"></div>
                        <a href="{{ route('settings.api') }}" class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('settings.api') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                            🔑 <span>API Keys</span>
                            @if(auth()->user()?->currentPlan() === 'starter')
                            <span class="ml-auto text-xs font-bold text-violet-400">Pro</span>
                            @endif
                        </a>
                    </div>
                </div>

                @if(auth()->user()?->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-violet-400 hover:text-violet-300 hover:bg-slate-800/60 transition-all">
                    Admin
                </a>
                @endif
            </div>

            {{-- User Menu --}}
            <div class="flex items-center gap-3">
                @if(auth()->user()?->onTrial() && !auth()->user()?->hasActiveSubscription() && !auth()->user()?->is_admin)
                    <div class="flex items-center gap-2 px-3 py-1 bg-amber-500/10 border border-amber-500/20 rounded-full">
                        <span class="text-xs font-semibold text-amber-500">
                            ⏳ {{ auth()->user()->daysLeftOnTrial() }} days left
                        </span>
                        <a href="{{ route('upgrade') }}" wire:navigate class="text-[10px] font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 px-2 py-0.5 rounded-full transition-colors">
                            Upgrade
                        </a>
                    </div>
                @endif
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-slate-200">{{ auth()->user()?->name }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()?->email }}</p>
                </div>
                <a href="{{ route('profile') }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700/50 text-sm text-slate-300 transition-all">
                    Profile
                </a>
                <button wire:click="logout" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700/50 text-sm text-slate-300 transition-all">
                    Logout
                </button>
            </div>
        </div>
    </div>
</nav>
