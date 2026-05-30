<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white text-sm transition-all">← Back to Dashboard</a>
                <h1 class="text-2xl font-bold text-white mt-2">Custom Invoice Designer 🎨</h1>
                <p class="text-slate-400 text-sm mt-1">Configure layout, colors, and fonts for all generated PDFs.</p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 font-semibold text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Dual-Pane Layout --}}
        <div class="grid lg:grid-cols-12 gap-8 items-start relative">

            @if($plan === 'starter')
            {{-- Frosted Premium locked overlay for Starter Users --}}
            <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-md rounded-3xl z-40 flex items-center justify-center p-8">
                <div class="max-w-md w-full rounded-2xl bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800 p-8 text-center shadow-2xl">
                    <span class="text-5xl block mb-4">💎</span>
                    <h3 class="text-xl font-bold text-white mb-2">Unlock Custom Invoice Designer</h3>
                    <p class="text-slate-400 text-sm mb-6 leading-relaxed">
                        Stand out with clients by uploading your logo, modifying fonts, toggling invoice columns, and customizing signature brand colors.
                    </p>
                    <a href="{{ route('upgrade') }}" class="inline-block w-full py-3 px-4 rounded-xl bg-gradient-to-r from-violet-500 to-indigo-500 hover:opacity-90 text-white font-bold text-sm shadow-lg transition-all">
                        Upgrade to Pro Plan — $19/mo
                    </a>
                </div>
            </div>
            @endif

            {{-- Left Pane: Controls (5 columns) --}}
            <div class="lg:col-span-5 bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-6">
                
                @if($businesses->count() > 1)
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Select Business</label>
                    <select wire:model.live="selectedBusinessId" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40">
                        @foreach($businesses as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <h3 class="text-sm font-bold text-slate-200 border-b border-slate-800 pb-3">Design Customization</h3>

                @php
                    $templateOptions = [
                        'modern' => 'Modern — Sleek & Clean',
                        'classic' => 'Classic — Professional Traditional',
                        'minimalist' => 'Minimalist — Ultra clean & light',
                        'corporate' => 'Corporate — Heavy & Boxed',
                        'bold' => 'Bold — Large Typography',
                        'elegant' => 'Elegant — Serif Emphasis',
                        'tech' => 'Tech/Startup — Rounded & Accent',
                        'studio' => 'Studio — Asymmetrical',
                        'monospace' => 'Monospace — Terminal Style',
                        'geometric' => 'Geometric — Sharp Edges',
                        'agency' => 'Creative Agency — Vibrant Blocks',
                        'vintage' => 'Vintage — Double Borders',
                        'high_contrast' => 'High Contrast — Strict B/W',
                        'pastel' => 'Pastel — Soft & Light',
                        'brutalist' => 'Brutalist — Raw Utility',
                        'compact' => 'Compact — Space Saving',
                        'neon' => 'Neon — Dark Mode Simulated',
                        'newspaper' => 'Newspaper — Editorial',
                        'retail' => 'Retail — Receipt Style',
                        'executive' => 'Executive — Centered & Formal'
                    ];
                    
                    $fontOptions = [
                        'Inter' => 'Inter — Sans-Serif',
                        'Roboto' => 'Roboto — Modern Sans',
                        'Outfit' => 'Outfit — Premium Tech',
                        'Lora' => 'Lora — Elegant Serif',
                        'Courier Prime' => 'Courier Prime — Monospace',
                        'Montserrat' => 'Montserrat — Sans-Serif',
                        'Open Sans' => 'Open Sans — Sans-Serif',
                        'Poppins' => 'Poppins — Sans-Serif',
                        'Playfair Display' => 'Playfair Display — Serif',
                        'Merriweather' => 'Merriweather — Serif',
                        'Space Mono' => 'Space Mono — Monospace',
                        'Fira Code' => 'Fira Code — Monospace',
                        'Oswald' => 'Oswald — Sans-Serif',
                        'Raleway' => 'Raleway — Sans-Serif',
                        'Lato' => 'Lato — Sans-Serif',
                        'Nunito' => 'Nunito — Sans-Serif',
                        'Ubuntu' => 'Ubuntu — Sans-Serif',
                        'Source Serif Pro' => 'Source Serif Pro — Serif',
                        'Inconsolata' => 'Inconsolata — Monospace',
                        'Dancing Script' => 'Dancing Script — Cursive'
                    ];
                @endphp

                {{-- Template Style --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Layout Template</label>
                    <div x-data="{ 
                        open: false, 
                        search: '', 
                        selected: @entangle('templateStyle').live,
                        options: {{ json_encode($templateOptions) }},
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            const result = {};
                            for (const [key, value] of Object.entries(this.options)) {
                                if (value.toLowerCase().includes(this.search.toLowerCase())) {
                                    result[key] = value;
                                }
                            }
                            return result;
                        }
                    }" class="relative" @click.away="open = false; search = ''">
                        <div @click="open = !open" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm cursor-pointer flex justify-between items-center hover:bg-slate-700/50 transition-colors">
                            <span x-text="options[selected] || 'Select Template'"></span>
                            <span class="text-slate-500 text-[10px]">▼</span>
                        </div>
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-1 bg-slate-800 border border-slate-700 rounded-lg shadow-2xl overflow-hidden" style="display: none;">
                            <div class="p-2 border-b border-slate-700">
                                <input x-model="search" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded text-sm text-slate-200 focus:outline-none focus:border-cyan-500/50" placeholder="Search layouts...">
                            </div>
                            <div class="max-h-60 overflow-y-auto">
                                <template x-for="(label, value) in filteredOptions" :key="value">
                                    <div @click="selected = value; open = false; search = ''" 
                                         class="px-3 py-2.5 text-sm cursor-pointer transition-colors border-b border-slate-700/30 last:border-0"
                                         :class="selected === value ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white'"
                                         x-text="label"></div>
                                </template>
                                <div x-show="Object.keys(filteredOptions).length === 0" class="px-3 py-4 text-sm text-center text-slate-500">No matching templates.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Font Family --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Font Family</label>
                    <div x-data="{ 
                        open: false, 
                        search: '', 
                        selected: @entangle('fontFamily').live,
                        options: {{ json_encode($fontOptions) }},
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            const result = {};
                            for (const [key, value] of Object.entries(this.options)) {
                                if (value.toLowerCase().includes(this.search.toLowerCase())) {
                                    result[key] = value;
                                }
                            }
                            return result;
                        }
                    }" class="relative" @click.away="open = false; search = ''">
                        <div @click="open = !open" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm cursor-pointer flex justify-between items-center hover:bg-slate-700/50 transition-colors">
                            <span x-text="options[selected] || 'Select Font'"></span>
                            <span class="text-slate-500 text-[10px]">▼</span>
                        </div>
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-1 bg-slate-800 border border-slate-700 rounded-lg shadow-2xl overflow-hidden" style="display: none;">
                            <div class="p-2 border-b border-slate-700">
                                <input x-model="search" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded text-sm text-slate-200 focus:outline-none focus:border-cyan-500/50" placeholder="Search fonts...">
                            </div>
                            <div class="max-h-60 overflow-y-auto">
                                <template x-for="(label, value) in filteredOptions" :key="value">
                                    <div @click="selected = value; open = false; search = ''" 
                                         class="px-3 py-2.5 text-sm cursor-pointer transition-colors border-b border-slate-700/30 last:border-0"
                                         :class="selected === value ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white'"
                                         x-text="label"
                                         :style="`font-family: '${value}', sans-serif;`"></div>
                                </template>
                                <div x-show="Object.keys(filteredOptions).length === 0" class="px-3 py-4 text-sm text-center text-slate-500">No matching fonts.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Colors --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Primary Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model.live="primaryColor" class="h-10 w-10 border border-slate-700 bg-transparent rounded cursor-pointer" />
                            <input type="text" wire:model.live="primaryColor" class="w-full px-2 py-2 rounded bg-slate-800 border border-slate-700 text-slate-200 text-xs focus:outline-none" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Accent Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model.live="accentColor" class="h-10 w-10 border border-slate-700 bg-transparent rounded cursor-pointer" />
                            <input type="text" wire:model.live="accentColor" class="w-full px-2 py-2 rounded bg-slate-800 border border-slate-700 text-slate-200 text-xs focus:outline-none" />
                        </div>
                    </div>
                </div>

                <h3 class="text-sm font-bold text-slate-200 border-b border-slate-800 pb-3 pt-2">Visible Columns & Sections</h3>

                {{-- Toggles --}}
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="showTax" class="rounded bg-slate-800 border-slate-700 text-cyan-500 focus:ring-cyan-500/40" />
                        <span class="text-sm text-slate-300">Show Tax calculation rows</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="showQty" class="rounded bg-slate-800 border-slate-700 text-cyan-500 focus:ring-cyan-500/40" />
                        <span class="text-sm text-slate-300">Show Quantity/Unit price columns</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="showNotes" class="rounded bg-slate-800 border-slate-700 text-cyan-500 focus:ring-cyan-500/40" />
                        <span class="text-sm text-slate-300">Show Notes & Terms footer block</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="showTagline" class="rounded bg-slate-800 border-slate-700 text-cyan-500 focus:ring-cyan-500/40" />
                        <span class="text-sm text-slate-300">Show Business Tagline under logo</span>
                    </label>
                </div>

                <h3 class="text-sm font-bold text-slate-200 border-b border-slate-800 pb-3 pt-2">Branding Text</h3>

                {{-- Tagline & Footer --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Business Tagline</label>
                        <input type="text" wire:model.live="tagline" placeholder="e.g. Premium Design Agency" class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Invoice Footer Notes</label>
                        <textarea wire:model.live="invoiceFooter" rows="3" placeholder="e.g. Thank you for your business! Net 30 payments." class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none"></textarea>
                    </div>
                </div>

                <div class="pt-4">
                    <button wire:click="save" class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950 font-bold text-sm shadow-lg hover:opacity-90 transition-all">
                        Save Design Settings
                    </button>
                </div>
            </div>

            {{-- Right Pane: Live Preview (7 columns) --}}
            <div class="lg:col-span-7 bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-4 sticky top-24">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-slate-200">Live Mockup Preview</h3>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest bg-slate-800 px-2 py-0.5 rounded">PDF Simulation</span>
                </div>

                {{-- Mockup Invoice Sheet --}}
                <div class="bg-white text-slate-800 rounded-xl p-6 shadow-2xl overflow-hidden border border-slate-200" 
                     style="font-family: '{{ $fontFamily }}', sans-serif;">
                    
                    {{-- Header Styles --}}
                    @include('pdf.templates_preview')

                    {{-- Client details & dates --}}
                    <div class="grid grid-cols-2 gap-4 my-6 text-xs">
                        <div>
                            <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Billed To</p>
                            <p class="font-bold text-slate-800">John Client</p>
                            <p class="text-slate-500">Acme Tech Systems</p>
                            <p class="text-slate-500">john@acmetech.com</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Payment Info</p>
                            <p class="text-slate-600">Issued: May 28, 2026</p>
                            <p class="text-slate-600">Due: June 27, 2026</p>
                        </div>
                    </div>

                    {{-- Line Item Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs mb-6 border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200" style="border-bottom-color: {{ $accentColor }};">
                                    <th class="py-2 text-slate-400 font-bold uppercase tracking-wider text-[10px]">Description</th>
                                    @if($showQty)
                                    <th class="py-2 text-slate-400 font-bold uppercase tracking-wider text-[10px] text-center">Qty</th>
                                    <th class="py-2 text-slate-400 font-bold uppercase tracking-wider text-[10px] text-right">Unit Price</th>
                                    @endif
                                    <th class="py-2 text-slate-400 font-bold uppercase tracking-wider text-[10px] text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-3">
                                        <p class="font-bold text-slate-800">Premium Branding Package</p>
                                        <p class="text-slate-400 text-[10px]">Corporate identity guidelines and color palettes.</p>
                                    </td>
                                    @if($showQty)
                                    <td class="py-3 text-center text-slate-600">1.00</td>
                                    <td class="py-3 text-right text-slate-600">$1,500.00</td>
                                    @endif
                                    <td class="py-3 text-right font-semibold text-slate-800">$1,500.00</td>
                                </tr>
                                <tr>
                                    <td class="py-3">
                                        <p class="font-bold text-slate-800">React/NextJS Landing Page</p>
                                        <p class="text-slate-400 text-[10px]">Custom responsive marketing website design.</p>
                                    </td>
                                    @if($showQty)
                                    <td class="py-3 text-center text-slate-600">2.00</td>
                                    <td class="py-3 text-right text-slate-600">$800.00</td>
                                    @endif
                                    <td class="py-3 text-right font-semibold text-slate-800">$1,600.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Totals --}}
                    <div class="flex justify-end text-xs">
                        <div class="w-1/2 space-y-1.5 border-t pt-3 border-slate-100">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Subtotal</span>
                                <span class="font-bold text-slate-700">$3,100.00</span>
                            </div>
                            @if($showTax)
                            <div class="flex justify-between">
                                <span class="text-slate-400">Tax (5%)</span>
                                <span class="font-bold text-slate-700">$155.00</span>
                            </div>
                            @endif
                            <div class="flex justify-between border-t border-slate-100 pt-2 font-black text-sm text-slate-900">
                                <span>Total Amount</span>
                                <span style="color: {{ $primaryColor }};">$3,255.00</span>
                            </div>
                        </div>
                    </div>

                    {{-- Notes / Signature Footer --}}
                    @if($showNotes && $invoiceFooter)
                        <div class="mt-8 pt-4 border-t border-slate-100 text-[11px] text-slate-400 italic">
                            <p class="font-bold text-slate-500 not-italic mb-1">Notes & Terms:</p>
                            <p>{{ $invoiceFooter }}</p>
                        </div>
                    @endif
                </div>

                <div class="p-3 bg-slate-800/40 rounded-xl text-center text-xs text-slate-400 leading-relaxed border border-slate-800/80">
                    💡 <strong>Pro Tip:</strong> Adjust primary/accent colors to match your business brand theme. Fonts are rendered in real-time on all printable outputs.
                </div>
            </div>

        </div>
    </div>
</div>
