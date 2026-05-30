<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'InvoiceFlow' }}</title>
    <meta name="description" content="InvoiceFlow - Simple, powerful invoicing for modern businesses." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;700;900&family=Roboto:wght@400;500;700;900&family=Lora:wght@400;500;700;900&family=Courier+Prime:wght@400;700&family=Montserrat:wght@400;700;900&family=Open+Sans:wght@400;700;900&family=Poppins:wght@400;700;900&family=Playfair+Display:wght@400;700;900&family=Merriweather:wght@400;700;900&family=Space+Mono:wght@400;700&family=Fira+Code:wght@400;700&family=Oswald:wght@400;700;900&family=Raleway:wght@400;700;900&family=Lato:wght@400;700;900&family=Nunito:wght@400;700;900&family=Ubuntu:wght@400;700;900&family=Source+Serif+Pro:wght@400;700;900&family=Inconsolata:wght@400;700;900&family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="session-success" content="{{ session('success', '') }}">
    <meta name="session-error" content="{{ session('error', '') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <style>
        body { font-family: 'Outfit', sans-serif; }
        /* Custom Toastr Premium Styling */
        #toast-container > div {
            opacity: 1 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(0, 0, 0, 0.4) !important;
            border-radius: 16px !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            padding: 16px 20px 16px 52px !important;
            width: 340px !important;
            background-position: 18px 18px !important;
            background-repeat: no-repeat !important;
            background-size: 22px !important;
            transition: all 0.2s ease-in-out !important;
        }
        #toast-container > .toast-success {
            background-color: #0f172a !important; /* slate-900 */
            border: 1px solid rgba(20, 184, 166, 0.3) !important; /* teal-500/30 */
            color: #2dd4bf !important; /* teal-400 */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%232dd4bf'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/%3E%3C/svg%3E") !important;
        }
        #toast-container > .toast-error {
            background-color: #0f172a !important; /* slate-900 */
            border: 1px solid rgba(239, 68, 68, 0.3) !important; /* red-500/30 */
            color: #f87171 !important; /* red-400 */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23f87171'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'/%3E%3C/svg%3E") !important;
        }
        #toast-container > .toast-info {
            background-color: #0f172a !important; /* slate-900 */
            border: 1px solid rgba(59, 130, 246, 0.3) !important; /* blue-500/30 */
            color: #60a5fa !important; /* blue-400 */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2360a5fa'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'/%3E%3C/svg%3E") !important;
        }
        #toast-container > .toast-warning {
            background-color: #0f172a !important; /* slate-900 */
            border: 1px solid rgba(245, 158, 11, 0.3) !important; /* amber-500/30 */
            color: #fbbf24 !important; /* amber-400 */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23fbbf24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'/%3E%3C/svg%3E") !important;
        }
        #toast-container > div:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.5) !important;
            transform: translateY(-2px);
        }
        #toast-container > .toast-success .toast-progress {
            background-color: #2dd4bf !important;
            opacity: 0.4 !important;
        }
        #toast-container > .toast-error .toast-progress {
            background-color: #f87171 !important;
            opacity: 0.4 !important;
        }
        #toast-container > .toast-info .toast-progress {
            background-color: #60a5fa !important;
            opacity: 0.4 !important;
        }
        #toast-container > .toast-warning .toast-progress {
            background-color: #fbbf24 !important;
            opacity: 0.4 !important;
        }
        .toast-title {
            font-weight: 700 !important;
            color: #fff !important;
            margin-bottom: 4px !important;
        }
        .toast-message {
            color: #94a3b8 !important; /* slate-400 */
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100">

<div class="min-h-screen flex flex-col">

    {{-- Navigation --}}
    <livewire:layout.navigation />

    {{-- Flash Messages (handled via Toastr) --}}

    {{-- Main Content --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-900 py-4 text-center text-xs text-slate-600">
        &copy; {{ date('Y') }} InvoiceFlow &mdash; Modern Invoicing for Modern Businesses
    </footer>

</div>

@livewireScripts
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "4000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    function showSessionToasts() {
        const successMeta = document.querySelector('meta[name="session-success"]');
        const errorMeta = document.querySelector('meta[name="session-error"]');
        
        console.log('Checking session toasts: success =', successMeta?.getAttribute('content'), 'error =', errorMeta?.getAttribute('content'));
        
        if (successMeta && successMeta.getAttribute('content')) {
            toastr.success(successMeta.getAttribute('content'));
            successMeta.setAttribute('content', '');
        }
        if (errorMeta && errorMeta.getAttribute('content')) {
            toastr.error(errorMeta.getAttribute('content'));
            errorMeta.setAttribute('content', '');
        }
    }

    document.addEventListener('DOMContentLoaded', showSessionToasts);
    document.addEventListener('livewire:navigated', showSessionToasts);

    // Listen to standard browser events
    window.addEventListener('notify', event => {
        console.log('Notify browser event received:', event.detail);
        let data = event.detail;
        if (Array.isArray(data)) {
            data = data[0];
        }
        if (data && data.type && data.message) {
            toastr[data.type](data.message);
        }
    });

    // Fallback for Livewire component event listener
    document.addEventListener('livewire:init', () => {
        Livewire.on('notify', (event) => {
            console.log('Notify Livewire event received:', event);
            let data = event[0] || event;
            if (data && data.type && data.message) {
                toastr[data.type](data.message);
            }
        });
    });
</script>
<x-cookie-consent />
</body>
</html>
