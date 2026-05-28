<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-white">Developer API Settings</h1>
            <p class="text-slate-400 text-sm mt-1">Generate and manage your API access tokens.</p>
        </div>

        @if($plan === 'starter')
        {{-- Starter Paywall --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-12 text-center">
            <div class="text-5xl mb-4">🔒</div>
            <h2 class="text-xl font-bold text-white mb-2">API Access Requires Pro or Higher</h2>
            <p class="text-slate-400 mb-6">Integrate InvoiceFlow with your own tools, automate workflows, and build custom pipelines with our developer API.</p>
            <a href="{{ route('upgrade') }}" wire:navigate class="inline-block px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 hover:opacity-90 text-slate-950 font-black shadow-lg transition-all">
                ⭐ Upgrade to Pro to Unlock API Access
            </a>
        </div>
        @else

        {{-- Token Card --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-8 space-y-6">
            <div class="flex items-start gap-5">
                <div class="h-14 w-14 rounded-2xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-3xl shrink-0">🔑</div>
                <div>
                    <h2 class="text-lg font-bold text-slate-100">API Tokens</h2>
                    <p class="text-slate-400 text-sm mt-1">Use tokens to authenticate requests to the InvoiceFlow API. Treat your tokens like passwords — never share them.</p>
                    <div class="mt-2">
                        <span class="text-xs font-semibold text-slate-400">Active tokens: </span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-violet-500/10 text-violet-400 border border-violet-500/20">{{ $tokenCount }}</span>
                    </div>
                </div>
            </div>

            @if($tokenVisible && $token)
            <div class="rounded-xl bg-teal-500/5 border border-teal-500/20 p-4">
                <p class="text-xs font-semibold text-teal-400 mb-2 uppercase tracking-wide">⚠️ Copy this token now — it will never be shown again!</p>
                <div class="flex items-center gap-3">
                    <code class="flex-1 break-all text-sm font-mono text-teal-300 bg-slate-900 rounded-lg p-3 border border-slate-800">{{ $token }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $token }}')"
                        class="shrink-0 px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-slate-300 text-xs font-semibold hover:bg-slate-700 transition-all">
                        Copy
                    </button>
                </div>
            </div>
            @endif

            <div class="flex gap-3">
                <button wire:click="generateToken" id="btn-generate-token"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-500 to-indigo-500 hover:opacity-90 text-white font-bold text-sm shadow-lg shadow-violet-500/20 transition-all">
                    Generate New Token
                </button>
                @if($tokenCount > 0)
                <button wire:click="revokeAllTokens" wire:confirm="Revoke ALL API tokens? Any integrations using them will stop working."
                    class="px-5 py-2.5 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-semibold hover:bg-red-500/20 transition-all">
                    Revoke All Tokens
                </button>
                @endif
            </div>
        </div>

        @if($plan === 'agency')
        {{-- Agency: Webhooks Section --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-8 space-y-4">
            <div class="flex items-start gap-4">
                <div class="h-12 w-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-2xl shrink-0">🔗</div>
                <div>
                    <h2 class="text-lg font-bold text-slate-100">Webhooks <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Agency</span></h2>
                    <p class="text-slate-400 text-sm mt-1">Receive real-time HTTP POST notifications when invoices are created, paid, or become overdue. Full API access includes unlimited webhook endpoints.</p>
                </div>
            </div>
            <div class="rounded-xl bg-slate-800/50 border border-slate-700/50 p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-2">Available Events</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['invoice.created','invoice.paid','invoice.overdue','client.created'] as $event)
                    <span class="px-2 py-1 rounded-lg bg-slate-700/60 border border-slate-600/50 text-xs font-mono text-slate-300">{{ $event }}</span>
                    @endforeach
                </div>
            </div>
            <p class="text-xs text-slate-500">Webhook endpoint configuration coming soon. Contact support to set up custom webhooks for your account.</p>
        </div>
        @endif

        {{-- API Docs Preview --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-8 space-y-5">
            <h2 class="text-lg font-bold text-slate-100">API Reference</h2>
            <p class="text-slate-400 text-sm">Include your token in the <code class="text-cyan-400 bg-slate-800 px-1.5 py-0.5 rounded text-xs">Authorization</code> header of every request.</p>

            @php
                $endpoints = [
                    ['method' => 'GET', 'path' => '/api/invoices', 'desc' => 'List all your invoices'],
                    ['method' => 'POST', 'path' => '/api/invoices', 'desc' => 'Create a new invoice'],
                    ['method' => 'GET', 'path' => '/api/invoices/{id}', 'desc' => 'Retrieve a single invoice'],
                    ['method' => 'PATCH', 'path' => '/api/invoices/{id}/status', 'desc' => 'Update invoice status'],
                    ['method' => 'GET', 'path' => '/api/clients', 'desc' => 'List all your clients'],
                    ['method' => 'POST', 'path' => '/api/clients', 'desc' => 'Create a new client'],
                ];
                $methodColors = ['GET' => 'bg-teal-500/10 text-teal-400 border-teal-500/20', 'POST' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20', 'PATCH' => 'bg-amber-500/10 text-amber-400 border-amber-500/20'];
            @endphp

            <div class="space-y-2">
                @foreach($endpoints as $ep)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 hover:border-slate-700 transition-all">
                    <span class="px-2 py-0.5 rounded-md text-xs font-bold border {{ $methodColors[$ep['method']] ?? '' }} w-14 text-center shrink-0">{{ $ep['method'] }}</span>
                    <code class="font-mono text-sm text-slate-200 flex-1">{{ $ep['path'] }}</code>
                    <span class="text-xs text-slate-500 hidden sm:block">{{ $ep['desc'] }}</span>
                </div>
                @endforeach
            </div>

            <div class="mt-4 rounded-xl bg-slate-800/60 border border-slate-700 p-4">
                <p class="text-xs text-slate-400 font-semibold mb-2">Example Request:</p>
                <pre class="text-xs font-mono text-cyan-300 overflow-x-auto">curl -X GET https://yourdomain.com/api/invoices \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"</pre>
            </div>
        </div>
        @endif

    </div>
</div>
