<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-white">Custom Branding</h1>
            <p class="text-slate-400 text-sm mt-1">Personalise how your invoices look when clients receive them.</p>
        </div>

        @if($plan !== 'agency')
        {{-- Paywall --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-12 text-center">
            <div class="text-5xl mb-4">🎨</div>
            <h2 class="text-xl font-bold text-white mb-2">Custom Branding is an Agency Feature</h2>
            <p class="text-slate-400 mb-6">Add your logo, choose your brand colours, and add a custom footer to every invoice you send. Make every invoice feel on-brand and professional.</p>
            <a href="{{ route('upgrade') }}" wire:navigate class="inline-block px-6 py-3 rounded-xl bg-gradient-to-r from-violet-500 to-indigo-500 hover:opacity-90 text-white font-black shadow-lg transition-all">
                ⭐ Upgrade to Agency to Unlock Custom Branding
            </a>
        </div>
        @else

        @if($businesses->isEmpty())
        <div class="rounded-2xl bg-slate-900 border border-amber-500/20 bg-amber-500/5 p-8 text-center">
            <div class="text-4xl mb-3">🏢</div>
            <h2 class="text-lg font-bold text-white mb-2">No Business Profiles Yet</h2>
            <p class="text-slate-400 mb-4">You need at least one business profile before customising branding.</p>
            <a href="{{ route('settings.businesses') }}" wire:navigate class="inline-block px-5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 font-bold hover:bg-slate-700 transition-all text-sm">
                → Go to Businesses
            </a>
        </div>
        @else

        {{-- Business Selector --}}
        @if($businesses->count() > 1)
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
            <label class="block text-sm font-semibold text-slate-300 mb-2">Select Business Profile to Brand</label>
            <select wire:model.live="selectedBusinessId" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/40">
                @foreach($businesses as $b)
                <option value="{{ $b->id }}">{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        {{-- Logo Upload --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-8 space-y-5">
            <h2 class="text-lg font-bold text-slate-100">Business Logo</h2>
            <p class="text-slate-400 text-sm">Upload your business logo. It will appear at the top of every invoice. Recommended: PNG or SVG with transparent background, max 2MB.</p>

            {{-- Existing logo preview --}}
            @if($existingLogo)
            <div class="flex items-center gap-4">
                <div class="h-20 w-40 rounded-xl bg-white/5 border border-slate-700 flex items-center justify-center p-3 overflow-hidden">
                    <img src="{{ Storage::url($existingLogo) }}" alt="Business logo" class="max-h-full max-w-full object-contain" />
                </div>
                <div>
                    <p class="text-sm text-slate-300 font-semibold">Current Logo</p>
                    <button wire:click="removeLogo" wire:confirm="Remove this logo?" class="mt-2 text-xs text-red-400 hover:text-red-300 transition-colors font-semibold">
                        🗑 Remove logo
                    </button>
                </div>
            </div>
            @endif

            {{-- Upload area --}}
            <div
                x-data="{ dragging: false }"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="dragging = false"
                class="relative"
            >
                <label for="logo-upload"
                    :class="dragging ? 'border-cyan-400 bg-cyan-500/10' : 'border-slate-700 hover:border-slate-600'"
                    class="flex flex-col items-center justify-center gap-3 w-full py-10 rounded-2xl border-2 border-dashed cursor-pointer transition-all bg-slate-950/50">
                    <div class="text-3xl">
                        @if($logo)
                        ✅
                        @else
                        📤
                        @endif
                    </div>
                    @if($logo)
                        <p class="text-sm font-semibold text-teal-400">New logo selected — ready to save!</p>
                        {{-- Preview new upload --}}
                        <div class="h-16 w-32 rounded-lg bg-white/5 border border-slate-700 flex items-center justify-center p-2 overflow-hidden">
                            <img src="{{ $logo->temporaryUrl() }}" alt="New logo preview" class="max-h-full max-w-full object-contain" />
                        </div>
                        <button type="button" wire:click="$set('logo', null)" class="text-xs text-red-400 hover:text-red-300 transition-colors">✕ Clear selection</button>
                    @else
                        <p class="text-sm text-slate-400">Drag & drop your logo here, or <span class="text-cyan-400 font-semibold">click to browse</span></p>
                        <p class="text-xs text-slate-500">PNG, JPG, SVG · Max 2MB</p>
                    @endif
                    <input id="logo-upload" wire:model="logo" type="file" accept="image/*" class="hidden" />
                </label>
                @error('logo') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                <div wire:loading wire:target="logo" class="absolute inset-0 flex items-center justify-center rounded-2xl bg-slate-900/70 backdrop-blur-sm">
                    <div class="text-cyan-400 font-semibold text-sm animate-pulse">Uploading…</div>
                </div>
            </div>
        </div>

        {{-- Brand Colours --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-8 space-y-6">
            <h2 class="text-lg font-bold text-slate-100">Brand Colours</h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Primary Colour</label>
                    <div class="flex items-center gap-3">
                        <input wire:model.live="primaryColor" type="color" class="h-10 w-16 rounded-lg bg-slate-950 border border-slate-700 cursor-pointer p-1" />
                        <span class="text-sm font-mono text-slate-400">{{ $primaryColor }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Headings and totals on your invoices.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Accent Colour</label>
                    <div class="flex items-center gap-3">
                        <input wire:model.live="accentColor" type="color" class="h-10 w-16 rounded-lg bg-slate-950 border border-slate-700 cursor-pointer p-1" />
                        <span class="text-sm font-mono text-slate-400">{{ $accentColor }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Borders, badges, and highlights.</p>
                </div>
            </div>
        </div>

        {{-- Footer & Tagline --}}
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-8 space-y-4">
            <h2 class="text-lg font-bold text-slate-100">Invoice Text</h2>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Business tagline</label>
                <input wire:model.live="tagline" type="text" placeholder="e.g. Creative solutions for modern businesses"
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 text-sm" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Custom footer text</label>
                <textarea wire:model.live="invoiceFooter" rows="3" placeholder="e.g. Thank you for your business! Payment terms: 30 days."
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 text-sm"></textarea>
                <p class="text-xs text-slate-500 mt-1">Appears at the bottom of every invoice PDF.</p>
            </div>
        </div>

        {{-- Live Preview --}}
        <div class="rounded-2xl border p-6 space-y-3" style="border-color: {{ $primaryColor }}40; background: {{ $primaryColor }}08;">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Live Invoice Preview</h2>
            <div class="rounded-xl bg-white p-6 shadow-lg">
                <div class="flex justify-between items-start pb-4 mb-4 border-b" style="border-color: {{ $accentColor }}50;">
                    <div class="flex items-center gap-3">
                        {{-- Logo preview in the invoice --}}
                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="Logo" class="h-10 object-contain" />
                        @elseif($existingLogo)
                            <img src="{{ Storage::url($existingLogo) }}" alt="Logo" class="h-10 object-contain" />
                        @else
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center" style="background: {{ $primaryColor }}20;">
                                <span class="text-lg font-black" style="color: {{ $primaryColor }}">B</span>
                            </div>
                        @endif
                        <div>
                            <p class="text-base font-black" style="color: {{ $primaryColor }}">
                                {{ $selectedBusiness?->name ?? 'Your Business' }}
                            </p>
                            @if($tagline) <p class="text-xs" style="color: {{ $accentColor }}">{{ $tagline }}</p> @endif
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold text-white" style="background: {{ $primaryColor }}">INVOICE</span>
                </div>
                <div class="text-xs text-gray-400 pt-4 border-t mt-4" style="border-color: {{ $accentColor }}20;">
                    {{ $invoiceFooter ?: 'Your custom footer will appear here.' }}
                </div>
            </div>
        </div>

        <button wire:click="save" wire:loading.attr="disabled" class="px-6 py-3 rounded-xl bg-gradient-to-r from-violet-500 to-indigo-500 hover:opacity-90 text-white font-bold shadow-lg transition-all flex items-center gap-2">
            <span wire:loading wire:target="save" class="animate-spin">⏳</span>
            Save Branding
        </button>
        @endif
        @endif

    </div>
</div>
