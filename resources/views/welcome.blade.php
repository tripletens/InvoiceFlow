<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>InvoiceFlow — Simple, powerful invoicing for modern businesses</title>
    <meta name="description" content="InvoiceFlow is an API-first invoicing platform for freelancers, agencies and SMEs. Automate invoice generation, track payments, and integrate via API." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        /* Fade‑in‑up animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">

    {{-- Glow orbs --}}
    <div class="fixed top-0 left-1/4 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-0 right-1/4 w-96 h-96 bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>

    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 border-b border-slate-800 bg-slate-950/90 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-tr from-cyan-400 to-teal-500 flex items-center justify-center shadow-lg">
                    <span class="font-black text-slate-950 text-sm">IF</span>
                </div>
                <span class="font-bold text-lg text-white">InvoiceFlow</span>
            </div>
            <div class="hidden md:flex items-center gap-6 text-sm text-slate-400 font-medium">
                <a href="#features" class="hover:text-white transition-all">Features</a>
                <a href="#pricing" class="hover:text-white transition-all">Pricing</a>
                <a href="#faq" class="hover:text-white transition-all">FAQ</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-white transition-all font-medium">Log in</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold text-sm shadow-lg transition-all">
                    Get Started Free
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 mb-6">
            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
            API-First Invoice Platform
        </span>
        <h1 class="text-4xl sm:text-5xl md:text-7xl font-black tracking-tight mb-6 leading-tight">
            Invoicing that <br />
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 via-teal-400 to-emerald-400">works for you</span>
        </h1>
        <p class="text-slate-400 text-lg sm:text-xl max-w-2xl mx-auto mb-10">
            Create, send and track invoices in seconds. Automate reminders, accept payments, and access everything via developer API.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('register') }}" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-bold text-base shadow-xl shadow-teal-500/20 transition-all">
                Start for Free
            </a>
            <a href="#features" class="px-7 py-3.5 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-200 font-semibold text-base transition-all">
                See How It Works →
            </a>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-14">
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">Everything you need</h2>
            <p class="text-slate-400 max-w-xl mx-auto">Built for freelancers, agencies and SaaS founders who need professional invoicing without the complexity.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $features = [
                ['icon'=>'📄','title'=>'Smart Invoice Generation','desc'=>'Build invoices with dynamic line items. Tax calculated automatically. Invoice numbers auto-incremented.','color'=>'border-cyan-500/20 bg-cyan-500/5'],
                ['icon'=>'📤','title'=>'Email & Status Tracking','desc'=>'Track every invoice from Draft → Sent → Viewed → Paid. Know exactly where your money stands.','color'=>'border-teal-500/20 bg-teal-500/5'],
                ['icon'=>'👥','title'=>'Client Management','desc'=>'Maintain a clean client directory. Attach invoices to clients and track their history.','color'=>'border-blue-500/20 bg-blue-500/5'],
                ['icon'=>'🔌','title'=>'Developer API','desc'=>'REST API access to all your invoice data. Generate tokens and integrate with any platform.','color'=>'border-violet-500/20 bg-violet-500/5'],
                ['icon'=>'⚠️','title'=>'Overdue Alerts','desc'=>'Get warned when invoices are overdue. Never let a payment slip through the cracks again.','color'=>'border-red-500/20 bg-red-500/5'],
                ['icon'=>'📊','title'=>'Revenue Dashboard','desc'=>'See your total billed, collected, and outstanding amounts at a single glance.','color'=>'border-amber-500/20 bg-amber-500/5'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="rounded-2xl border {{ $f['color'] }} p-7 hover:scale-[1.01] transition-all duration-200">
                <span class="text-3xl mb-4 block">{{ $f['icon'] }}</span>
                <h3 class="text-lg font-bold text-white mb-2">{{ $f['title'] }}</h3>
                <p class="text-slate-400 text-sm leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Pricing --}}
    <section id="pricing" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-14">
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">Simple, honest pricing</h2>
            <p class="text-slate-400">No hidden fees. Cancel any time.</p>
        </div>
        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
            @php
            $plans = [
                ['name'=>'Free','price'=>'$0','period'=>'/mo','desc'=>'For freelancers just getting started.','features'=>['Up to 3 invoices/mo','1 Business profile','Client directory','PDF downloads'],'highlighted'=>false,'color'=>'border-slate-700'],
                ['name'=>'Starter','price'=>'$9','period'=>'/mo','desc'=>'For active freelancers and solopreneurs.','features'=>['Up to 25 invoices/mo','1 Business profile','Bank Transfer Details','Basic Income vs Expense','Standard email support'],'highlighted'=>false,'color'=>'border-slate-700'],
                ['name'=>'Pro','price'=>'$19','period'=>'/mo','desc'=>'For growing agencies and founders.','features'=>['Unlimited invoices','3 Business profiles','Developer API access','Automated reminders','Priority support'],'highlighted'=>true,'color'=>'border-cyan-500'],
                ['name'=>'Agency','price'=>'$59','period'=>'/mo','desc'=>'For large teams and enterprises.','features'=>['Unlimited everything','Unlimited Business profiles','Full API access','Custom branding','Dedicated support'],'highlighted'=>false,'color'=>'border-slate-700'],
            ];
            @endphp
            @foreach($plans as $plan)
            <div class="relative rounded-2xl border {{ $plan['color'] }} {{ $plan['highlighted'] ? 'bg-gradient-to-b from-cyan-500/10 to-teal-500/5' : 'bg-slate-900' }} p-8 flex flex-col">
                @if($plan['highlighted'])
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950">Most Popular</span>
                @endif
                <h3 class="text-xl font-black text-white">{{ $plan['name'] }}</h3>
                <div class="mt-3 mb-1">
                    <span class="text-4xl font-black text-white">{{ $plan['price'] }}</span>
                    <span class="text-slate-400 text-sm">{{ $plan['period'] }}</span>
                </div>
                <p class="text-slate-400 text-sm mb-6">{{ $plan['desc'] }}</p>
                <ul class="space-y-2 flex-1 mb-8">
                    @foreach($plan['features'] as $feature)
                    <li class="flex items-center gap-2 text-sm text-slate-300">
                        <span class="text-teal-400">✓</span> {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="text-center py-3 rounded-xl {{ $plan['highlighted'] ? 'bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950 font-bold shadow-lg' : 'bg-slate-800 border border-slate-700 text-slate-200' }} hover:opacity-90 font-semibold text-sm transition-all">
                    Get Started
                </a>
            </div>
            @endforeach
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20" x-data="{ open: null }">
        <h2 class="text-3xl font-black text-white text-center mb-10">Frequently Asked Questions</h2>
        <div class="space-y-3">
            @foreach([
                ['q'=>'Do I need a credit card to sign up?','a'=>'No! InvoiceFlow offers a free trial with no credit card required.'],
                ['q'=>'Can I use the API to integrate with my own tools?','a'=>'Yes. Every Pro and Agency plan includes full API access with Sanctum token authentication.'],
                ['q'=>'What currencies are supported?','a'=>'We support USD, EUR, GBP, NGN, and CAD. More currencies are being added regularly.'],
                ['q'=>'Can I cancel at any time?','a'=>'Absolutely. You can cancel your subscription at any time from your account settings.'],
            ] as $idx => $faq)
            <div class="rounded-xl bg-slate-900 border border-slate-800 overflow-hidden" x-data>
                <button @click="open === {{ $idx }} ? open = null : open = {{ $idx }}" class="w-full flex items-center justify-between px-6 py-4 text-left text-sm font-semibold text-slate-200 hover:text-white transition-all">
                    {{ $faq['q'] }}
                    <span x-text="open === {{ $idx }} ? '−' : '+'" class="text-cyan-400 font-mono text-lg ml-4 shrink-0"></span>
                </button>
                <div x-show="open === {{ $idx }}" x-collapse class="px-6 pb-4 text-sm text-slate-400 leading-relaxed">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="rounded-3xl bg-gradient-to-r from-cyan-500/20 via-slate-900 to-teal-500/20 border border-slate-800 p-12 text-center">
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">Ready to get paid faster?</h2>
            <p class="text-slate-400 mb-8">Join thousands of freelancers and businesses using InvoiceFlow.</p>
            <a href="{{ route('register') }}" class="inline-block px-8 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-black text-lg shadow-xl shadow-teal-500/20 transition-all">
                Start for Free Today
            </a>
        </div>
    </section>

    <footer class="border-t border-slate-900 py-8 text-center text-xs text-slate-600">
        &copy; 2026 FlowLedger. Built with ❤️ for Businesses. All rights reserved.
    </footer>

    <script src="//unpkg.com/alpinejs" defer></script>
    <x-cookie-consent />
</body>
</html>
