<div x-data="cookieConsent()" 
     x-show="showConsent" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-full"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-full"
     class="fixed bottom-0 inset-x-0 pb-2 sm:pb-5 z-[100]" 
     style="display: none;">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="p-4 rounded-xl bg-slate-900 border border-slate-700 shadow-2xl shadow-slate-950/50 flex items-center justify-between flex-wrap gap-4">
            <div class="flex-1 flex items-center gap-4">
                <span class="text-2xl hidden sm:block">🍪</span>
                <p class="text-sm font-medium text-slate-300">
                    <strong class="text-white">We value your privacy.</strong> 
                    We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic to comply with GDPR and CCPA regulations. By clicking "Accept All", you consent to our use of cookies.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <button @click="decline" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 transition-all">
                    Decline Essential Only
                </button>
                <button @click="accept" class="px-4 py-2 rounded-lg text-sm font-bold text-slate-950 bg-gradient-to-r from-cyan-400 to-teal-500 hover:opacity-90 transition-all shadow-lg shadow-cyan-500/20">
                    Accept All
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function cookieConsent() {
        return {
            showConsent: false,
            init() {
                if (!localStorage.getItem('cookie_consent')) {
                    setTimeout(() => {
                        this.showConsent = true;
                    }, 1000);
                }
            },
            accept() {
                localStorage.setItem('cookie_consent', 'accepted');
                this.showConsent = false;
            },
            decline() {
                localStorage.setItem('cookie_consent', 'declined');
                this.showConsent = false;
            }
        }
    }
</script>
