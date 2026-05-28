<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Welcome back, {{ auth()->user()->name }} 👋</h1>
                <p class="text-slate-400 text-sm mt-1">Here's your invoicing summary at a glance.</p>
            </div>
            <a href="{{ route('invoices.create') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-400 hover:to-teal-400 text-slate-950 font-bold text-sm shadow-lg shadow-teal-500/20 transition-all">
                + New Invoice
            </a>
        </div>

        {{-- Stats Grid --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-5">
            @php
                $userCurrency = auth()->user()->default_currency ?? 'USD';
                $symbols = ['USD' => '$', 'EUR' => '€', 'GBP' => '£', 'NGN' => '₦', 'CAD' => 'CA$'];
                $currencySymbol = $symbols[$userCurrency] ?? $userCurrency . ' ';

                $statCards = [
                    ['label' => 'Total Invoiced', 'value' => \App\Helpers\CurrencyHelper::format($stats['total_invoiced'], $userCurrency), 'icon' => '💰', 'color' => 'border-cyan-500/20 bg-cyan-500/5 hover:border-cyan-500/50', 'url' => route('invoices.index')],
                    ['label' => 'Paid Revenue', 'value' => \App\Helpers\CurrencyHelper::format($stats['paid'], $userCurrency), 'icon' => '✅', 'color' => 'border-teal-500/20 bg-teal-500/5 hover:border-teal-500/50', 'url' => route('invoices.index') . '?status=paid'],
                    ['label' => 'Total Expenses', 'value' => \App\Helpers\CurrencyHelper::format($totalExpenses, $userCurrency), 'icon' => '💸', 'color' => 'border-rose-500/20 bg-rose-500/5 hover:border-rose-500/50', 'url' => route('expenses.index')],
                    ['label' => 'Net Profit', 'value' => ($netProfit < 0 ? '-' : '') . \App\Helpers\CurrencyHelper::format(abs($netProfit), $userCurrency), 'icon' => '📈', 'color' => ($netProfit >= 0 ? 'border-emerald-500/20 bg-emerald-500/5 hover:border-emerald-500/50' : 'border-red-500/20 bg-red-500/5 hover:border-red-500/50'), 'url' => route('invoices.index')],
                    ['label' => 'Overdue Invoices', 'value' => $stats['overdue'], 'icon' => '⚠️', 'color' => 'border-orange-500/20 bg-orange-500/5 hover:border-orange-500/50', 'url' => route('invoices.index') . '?status=overdue'],
                ];
            @endphp
            @foreach($statCards as $card)
            <a href="{{ $card['url'] }}" wire:navigate class="rounded-2xl border {{ $card['color'] }} p-5 flex items-center gap-3 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                <span class="text-2xl">{{ $card['icon'] }}</span>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">{{ $card['label'] }}</p>
                    <p class="text-xl font-black text-white mt-0.5">{{ $card['value'] }}</p>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Analytical Charts --}}
        <div class="grid lg:grid-cols-3 gap-5">
            {{-- Monthly Revenue Trend --}}
            <div class="lg:col-span-2 rounded-2xl bg-slate-900 border border-slate-800 p-6 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-white">Monthly Invoicing Trend</h3>
                    <p class="text-xs text-slate-400">Total invoice amounts issued over the last 6 months.</p>
                </div>
                <div x-data="{
                    labels: @js($monthlyLabels),
                    values: @js($monthlyValues),
                    currencySymbol: @js($currencySymbol),
                    init() {
                        this.$nextTick(() => {
                            const ctx = this.$refs.canvas.getContext('2d');
                            const gradient = ctx.createLinearGradient(0, 0, 0, 240);
                            gradient.addColorStop(0, 'rgba(6, 182, 212, 0.25)');
                            gradient.addColorStop(1, 'rgba(6, 182, 212, 0)');

                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: this.labels,
                                    datasets: [{
                                        label: 'Invoiced Amount (' + this.currencySymbol + ')',
                                        data: this.values,
                                        borderColor: '#06b6d4',
                                        backgroundColor: gradient,
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4,
                                        pointBackgroundColor: '#06b6d4',
                                        pointBorderColor: '#0f172a',
                                        pointBorderWidth: 2,
                                        pointRadius: 5,
                                        pointHoverRadius: 7
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            backgroundColor: '#1e293b',
                                            titleColor: '#f1f5f9',
                                            bodyColor: '#cbd5e1',
                                            borderColor: '#334155',
                                            borderWidth: 1,
                                            padding: 10,
                                            displayColors: false,
                                            callbacks: {
                                                label: (context) => {
                                                    return 'Invoiced: ' + this.currencySymbol + context.raw.toLocaleString(undefined, {minimumFractionDigits: 2});
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                            ticks: {
                                                color: '#94a3b8',
                                                font: { family: 'Outfit', size: 11 },
                                                callback: (value) => { return this.currencySymbol + value; }
                                            }
                                        },
                                        x: {
                                            grid: { display: false },
                                            ticks: {
                                                color: '#94a3b8',
                                                font: { family: 'Outfit', size: 11 }
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    }
                }" class="h-64 relative w-full">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            {{-- Invoice Status Distribution --}}
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-white">Invoice Statuses</h3>
                    <p class="text-xs text-slate-400">Distribution of invoices by status.</p>
                </div>
                <div x-data="{
                    counts: @js($statusCounts),
                    init() {
                        this.$nextTick(() => {
                            const ctx = this.$refs.canvas.getContext('2d');
                            new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: Object.keys(this.counts),
                                    datasets: [{
                                        data: Object.values(this.counts),
                                        backgroundColor: [
                                            '#14b8a6', // Paid - Teal
                                            '#3b82f6', // Sent - Blue
                                            '#ef4444', // Overdue - Red
                                            '#64748b'  // Draft - Slate
                                        ],
                                        borderWidth: 2,
                                        borderColor: '#0f172a',
                                        hoverOffset: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                color: '#94a3b8',
                                                font: { family: 'Outfit', size: 11 },
                                                padding: 15,
                                                usePointStyle: true
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: '#1e293b',
                                            titleColor: '#f1f5f9',
                                            bodyColor: '#cbd5e1',
                                            borderColor: '#334155',
                                            borderWidth: 1,
                                            padding: 10,
                                            displayColors: true,
                                            callbacks: {
                                                label: function(context) {
                                                    return ' ' + context.label + ': ' + context.raw + ' invoice(s)';
                                                }
                                            }
                                        }
                                    },
                                    cutout: '75%'
                                }
                            });
                        });
                    }
                }" class="h-64 relative w-full flex items-center justify-center">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid md:grid-cols-3 gap-5">
            @php
                $actions = [
                    ['label' => 'Manage Clients', 'desc' => 'Add and update your client directory.', 'href' => route('clients.index'), 'color' => 'text-cyan-400 bg-cyan-500/10 border-cyan-500/20', 'icon' => '👥'],
                    ['label' => 'View All Invoices', 'desc' => 'Filter, track and update invoice statuses.', 'href' => route('invoices.index'), 'color' => 'text-teal-400 bg-teal-500/10 border-teal-500/20', 'icon' => '🧾'],
                    ['label' => 'API Settings', 'desc' => 'Generate your developer access tokens.', 'href' => route('settings.api'), 'color' => 'text-violet-400 bg-violet-500/10 border-violet-500/20', 'icon' => '🔑'],
                ];
            @endphp
            @foreach($actions as $action)
            <a href="{{ $action['href'] }}" class="group rounded-2xl border {{ $action['color'] }} p-5 hover:opacity-80 transition-all flex items-start gap-4">
                <span class="text-2xl">{{ $action['icon'] }}</span>
                <div>
                    <h3 class="font-bold text-white group-hover:opacity-80 transition-all">{{ $action['label'] }}</h3>
                    <p class="text-sm text-slate-400 mt-1">{{ $action['desc'] }}</p>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</div>
