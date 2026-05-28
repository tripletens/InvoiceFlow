<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Client Portal — InvoiceFlow' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen antialiased flex flex-col">

    {{-- Client Topbar --}}
    <nav class="sticky top-0 z-50 border-b border-slate-800 bg-slate-950/90 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                {{-- Logo --}}
                <div class="flex items-center gap-2.5">
                    <div class="h-8 w-8 rounded-lg bg-gradient-to-tr from-cyan-400 to-teal-500 flex items-center justify-center shadow-lg">
                        <span class="font-black text-slate-950 text-sm">CP</span>
                    </div>
                    <span class="font-bold text-lg text-white tracking-tight">ClientPortal</span>
                </div>

                {{-- User controls --}}
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        @php
                            $pClient = \App\Models\Client::find(session('client_id'));
                        @endphp
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-bold">Client Account</p>
                        <p class="text-sm font-semibold text-slate-200">{{ $pClient?->name ?? 'Portal User' }}</p>
                    </div>
                    
                    <form method="POST" action="{{ route('client.logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 hover:bg-slate-800 hover:border-slate-700 text-xs text-slate-300 transition-all font-semibold">
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    {{-- Main --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-900 py-6 text-center text-xs text-slate-600">
        &copy; {{ date('Y') }} InvoiceFlow &mdash; Secure Client Invoicing Portal
    </footer>

    @livewireScripts
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000"
        };
        
        window.addEventListener('notify', event => {
            let data = event.detail;
            if (Array.isArray(data)) data = data[0];
            if (data && data.type && data.message) {
                toastr[data.type](data.message);
            }
        });
    </script>
</body>
</html>
