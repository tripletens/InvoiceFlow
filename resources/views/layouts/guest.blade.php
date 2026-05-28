<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'InvoiceFlow') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen antialiased flex">

    <!-- Left Branding Side -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 border-r border-slate-800 flex-col justify-between p-12 overflow-hidden">
        <!-- Background Orbs -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none translate-x-1/3 translate-y-1/3"></div>
        
        <div class="relative z-10 flex items-center gap-3">
            <a href="/" wire:navigate class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-cyan-400 to-teal-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                    <span class="font-black text-slate-950 text-lg">IF</span>
                </div>
                <span class="font-bold text-2xl text-white">InvoiceFlow</span>
            </a>
        </div>

        <div class="relative z-10 max-w-md">
            <span class="inline-block py-1 px-3 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold mb-6 tracking-wider uppercase">Get Paid Faster</span>
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-6 leading-tight">Professional invoicing made effortlessly simple.</h1>
            <p class="text-slate-400 text-lg">Join thousands of modern businesses who use InvoiceFlow to create beautiful invoices, track clients, and get paid on time.</p>
        </div>

        <div class="relative z-10 text-slate-500 text-sm font-medium">
            &copy; {{ date('Y') }} InvoiceFlow Inc. All rights reserved.
        </div>
    </div>

    <!-- Right Form Side -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-500/5 rounded-full blur-3xl pointer-events-none translate-x-1/3 -translate-y-1/3"></div>
        
        <div class="w-full max-w-md relative z-10">
            <!-- Mobile Logo -->
            <div class="flex lg:hidden items-center justify-center gap-3 mb-10">
                <a href="/" wire:navigate class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-cyan-400 to-teal-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                        <span class="font-black text-slate-950 text-lg">IF</span>
                    </div>
                    <span class="font-bold text-2xl text-white">InvoiceFlow</span>
                </a>
            </div>

            <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl shadow-slate-950/50">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
