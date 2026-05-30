@if($templateStyle === 'modern')
    <div class="flex items-start justify-between border-b-4 pb-6" style="border-color: {{ $primaryColor }};">
        <div>
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded flex items-center justify-center font-bold text-white text-xs" style="background: {{ $primaryColor }};">LOGO</div>
                <span class="text-lg font-black text-slate-900">Acme Corporation</span>
            </div>
            @if($showTagline && $tagline)<p class="text-xs text-slate-500 mt-1 italic">{{ $tagline }}</p>@endif
            <p class="text-xs text-slate-400 mt-2">123 Business Rd, Suite 100</p>
        </div>
        <div class="text-right">
            <h1 class="text-2xl font-black uppercase tracking-tight" style="color: {{ $primaryColor }};">Invoice</h1>
            <p class="text-xs text-slate-400 mt-1">#INV-2026-001</p>
        </div>
    </div>
@elseif($templateStyle === 'classic')
    <div class="text-center border-b pb-6 border-slate-200">
        <div class="h-10 w-10 mx-auto rounded-full flex items-center justify-center font-bold text-white text-sm mb-2" style="background: {{ $primaryColor }};">L</div>
        <h2 class="text-xl font-bold text-slate-900">Acme Corporation</h2>
        @if($showTagline && $tagline)<p class="text-xs text-slate-500 italic mt-0.5">{{ $tagline }}</p>@endif
        <p class="text-xs text-slate-400 mt-1">123 Business Rd, Suite 100</p>
        <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center px-4">
            <span class="text-xs text-slate-400">#INV-2026-001</span>
            <h1 class="text-lg font-bold uppercase tracking-widest" style="color: {{ $primaryColor }};">Invoice</h1>
            <span class="text-xs text-slate-400">Date: May 28, 2026</span>
        </div>
    </div>
@elseif($templateStyle === 'minimalist')
    <div class="flex items-start justify-between border-b pb-4 border-slate-100">
        <div>
            <h2 class="text-base font-bold text-slate-900">Acme Corporation</h2>
            @if($showTagline && $tagline)<p class="text-[11px] text-slate-500 mt-0.5">{{ $tagline }}</p>@endif
        </div>
        <div class="text-right">
            <span class="text-xs text-slate-400 mr-4">#INV-2026-001</span>
            <span class="text-xs font-bold uppercase" style="color: {{ $primaryColor }};">Invoice</span>
        </div>
    </div>
@elseif($templateStyle === 'corporate')
    <div class="flex items-start justify-between bg-slate-50 p-6 border border-slate-200 rounded-lg mb-4">
        <div>
            <h1 class="text-xl font-black uppercase text-slate-800">Invoice</h1>
            <p class="text-xs font-semibold text-slate-500 mt-1">#INV-2026-001</p>
        </div>
        <div class="text-right border-l-4 pl-4" style="border-color: {{ $primaryColor }};">
            <h2 class="text-lg font-bold text-slate-900">Acme Corporation</h2>
            @if($showTagline && $tagline)<p class="text-xs text-slate-500 italic">{{ $tagline }}</p>@endif
        </div>
    </div>
@elseif($templateStyle === 'bold')
    <div class="pb-6 mb-4" style="border-bottom: 8px solid {{ $primaryColor }};">
        <h1 class="text-4xl font-black tracking-tighter uppercase text-slate-900 leading-none">INVOICE</h1>
        <div class="flex justify-between items-end mt-4">
            <div>
                <h2 class="text-xl font-bold" style="color: {{ $primaryColor }};">Acme Corporation</h2>
                @if($showTagline && $tagline)<p class="text-xs text-slate-500 font-medium">{{ $tagline }}</p>@endif
            </div>
            <p class="text-sm font-bold text-slate-500">#INV-2026-001</p>
        </div>
    </div>
@elseif($templateStyle === 'elegant')
    <div class="text-center pb-8 border-b border-slate-200 mb-6 relative">
        <div class="absolute top-0 left-0 w-full h-0.5" style="background: {{ $primaryColor }};"></div>
        <h2 class="text-2xl pt-6 font-medium text-slate-800 tracking-widest uppercase">Acme Corporation</h2>
        @if($showTagline && $tagline)<p class="text-xs text-slate-500 italic mt-1">{{ $tagline }}</p>@endif
        <h1 class="text-sm uppercase tracking-[0.3em] mt-6" style="color: {{ $primaryColor }};">Invoice</h1>
    </div>
@elseif($templateStyle === 'tech')
    <div class="flex items-center justify-between p-5 rounded-2xl mb-4" style="background: linear-gradient(135deg, {{ $primaryColor }}15, {{ $accentColor }}15);">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl flex items-center justify-center font-bold text-white shadow-lg" style="background: linear-gradient(135deg, {{ $primaryColor }}, {{ $accentColor }});">A</div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Acme Corporation</h2>
                @if($showTagline && $tagline)<p class="text-[10px] uppercase font-bold text-slate-500">{{ $tagline }}</p>@endif
            </div>
        </div>
        <div class="text-right bg-white py-1 px-3 rounded-lg shadow-sm">
            <h1 class="text-sm font-black uppercase" style="color: {{ $primaryColor }};">Invoice</h1>
            <p class="text-[10px] font-bold text-slate-400">#INV-2026-001</p>
        </div>
    </div>
@elseif($templateStyle === 'studio')
    <div class="flex mb-8">
        <div class="w-1/3 bg-slate-900 text-white p-6 rounded-l-xl" style="background: {{ $primaryColor }};">
            <h2 class="text-xl font-bold">Acme Corp</h2>
            @if($showTagline && $tagline)<p class="text-xs text-white/70 mt-2">{{ $tagline }}</p>@endif
        </div>
        <div class="w-2/3 p-6 flex flex-col justify-center items-end bg-slate-50 rounded-r-xl border border-l-0 border-slate-200">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">INVOICE</h1>
            <p class="text-xs font-bold text-slate-400 mt-1">#INV-2026-001</p>
        </div>
    </div>
@elseif($templateStyle === 'monospace')
    <div class="border-2 border-slate-800 p-4 mb-6 font-mono bg-slate-50">
        <div class="flex justify-between border-b-2 border-slate-800 pb-2 mb-2">
            <div class="font-bold">> ACME_CORPORATION</div>
            <div class="font-bold">> INVOICE</div>
        </div>
        @if($showTagline && $tagline)<div class="text-xs text-slate-600 mb-2">/* {{ $tagline }} */</div>@endif
        <div class="text-xs">ID: INV-2026-001</div>
    </div>
@elseif($templateStyle === 'geometric')
    <div class="relative overflow-hidden p-6 mb-6 rounded-xl border border-slate-200 bg-white">
        <div class="absolute top-0 right-0 w-32 h-32 transform translate-x-8 -translate-y-8 rounded-full" style="background: {{ $primaryColor }}15;"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 transform -translate-x-8 translate-y-8 rounded-full" style="background: {{ $accentColor }}15;"></div>
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Acme Corporation</h2>
                @if($showTagline && $tagline)<p class="text-xs font-semibold text-slate-500 uppercase mt-1">{{ $tagline }}</p>@endif
            </div>
            <div class="text-right">
                <h1 class="text-lg font-bold" style="color: {{ $primaryColor }};">INVOICE</h1>
            </div>
        </div>
    </div>
@elseif($templateStyle === 'agency')
    <div class="flex mb-6 h-24">
        <div class="w-4 h-full" style="background: {{ $primaryColor }};"></div>
        <div class="w-4 h-full mx-1" style="background: {{ $accentColor }};"></div>
        <div class="flex-1 flex justify-between items-center bg-slate-100 px-6">
            <div>
                <h2 class="text-2xl font-black text-slate-900">Acme Corporation</h2>
                @if($showTagline && $tagline)<p class="text-xs font-medium text-slate-600 mt-1">{{ $tagline }}</p>@endif
            </div>
            <h1 class="text-xl font-bold uppercase tracking-widest text-slate-400">Invoice</h1>
        </div>
    </div>
@elseif($templateStyle === 'vintage')
    <div class="p-1 mb-6 border-4" style="border-color: {{ $primaryColor }};">
        <div class="border border-slate-300 p-6 text-center bg-amber-50/30">
            <h2 class="text-3xl font-serif text-slate-800">Acme Corporation</h2>
            @if($showTagline && $tagline)<p class="text-xs font-serif italic text-slate-600 my-2">~ {{ $tagline }} ~</p>@endif
            <div class="mt-4 pt-4 border-t border-slate-300 flex justify-center items-center gap-6">
                <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Invoice</span>
                <span class="text-xs text-slate-400">No. 2026-001</span>
            </div>
        </div>
    </div>
@elseif($templateStyle === 'high_contrast')
    <div class="bg-black text-white p-6 mb-6">
        <div class="flex justify-between items-start border-b border-white/20 pb-4">
            <h1 class="text-3xl font-black uppercase">Invoice</h1>
            <div class="text-right">
                <h2 class="text-xl font-bold">Acme Corp</h2>
                @if($showTagline && $tagline)<p class="text-[10px] text-gray-400 mt-1 uppercase">{{ $tagline }}</p>@endif
            </div>
        </div>
        <div class="pt-4 text-xs text-gray-400">ID: INV-2026-001</div>
    </div>
@elseif($templateStyle === 'pastel')
    <div class="p-6 mb-6 rounded-3xl" style="background: {{ $primaryColor }}10;">
        <div class="text-center">
            <h2 class="text-2xl font-medium text-slate-700">Acme Corporation</h2>
            @if($showTagline && $tagline)<p class="text-xs text-slate-500 mt-1">{{ $tagline }}</p>@endif
            <div class="mt-4 inline-block px-4 py-1 rounded-full bg-white text-xs font-bold tracking-widest uppercase shadow-sm" style="color: {{ $primaryColor }};">Invoice</div>
        </div>
    </div>
@elseif($templateStyle === 'brutalist')
    <div class="border-4 border-black p-4 mb-6 bg-yellow-50 font-sans shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
        <div class="flex justify-between border-b-4 border-black pb-2 mb-2">
            <h2 class="text-2xl font-black uppercase tracking-tighter text-black">Acme Corp</h2>
            <h1 class="text-2xl font-black uppercase tracking-tighter bg-black text-yellow-50 px-2">Invoice</h1>
        </div>
        @if($showTagline && $tagline)<p class="text-sm font-bold border-b-2 border-black pb-2 mb-2 uppercase">{{ $tagline }}</p>@endif
        <p class="text-xs font-black uppercase">REF: INV-2026-001</p>
    </div>
@elseif($templateStyle === 'compact')
    <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-4">
        <div class="flex items-center gap-4">
            <h2 class="text-sm font-bold text-slate-800">Acme Corporation</h2>
            @if($showTagline && $tagline)<span class="text-[10px] text-slate-400 border-l border-slate-200 pl-4">{{ $tagline }}</span>@endif
        </div>
        <div class="flex items-center gap-4">
            <span class="text-[10px] text-slate-400 font-mono">INV-2026-001</span>
            <span class="text-xs font-bold uppercase py-0.5 px-2 bg-slate-100 rounded text-slate-600">Invoice</span>
        </div>
    </div>
@elseif($templateStyle === 'neon')
    <div class="bg-slate-950 p-6 mb-6 rounded-xl border border-slate-800" style="box-shadow: 0 0 20px {{ $primaryColor }}30;">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black tracking-wider" style="color: {{ $primaryColor }}; text-shadow: 0 0 10px {{ $primaryColor }}80;">ACME CORP</h2>
                @if($showTagline && $tagline)<p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">{{ $tagline }}</p>@endif
            </div>
            <div class="text-right">
                <h1 class="text-sm font-bold text-white uppercase tracking-widest">Invoice</h1>
                <p class="text-xs mt-1" style="color: {{ $accentColor }};">#INV-2026-001</p>
            </div>
        </div>
    </div>
@elseif($templateStyle === 'newspaper')
    <div class="border-b-4 border-double border-slate-800 pb-4 mb-6 text-center font-serif">
        <h1 class="text-4xl font-black text-slate-900 uppercase tracking-tighter">The Acme Corp.</h1>
        @if($showTagline && $tagline)<p class="text-xs italic text-slate-600 border-t border-b border-slate-300 py-1 my-2">{{ $tagline }}</p>@endif
        <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest mt-2 px-4">
            <span>Vol. 1</span>
            <span>Invoice Statement</span>
            <span>No. 2026-001</span>
        </div>
    </div>
@elseif($templateStyle === 'retail')
    <div class="w-full max-w-[250px] mx-auto border border-dashed border-slate-300 p-4 mb-6 text-center bg-slate-50 font-mono text-xs">
        <h2 class="text-base font-bold text-slate-900 uppercase">Acme Corp</h2>
        @if($showTagline && $tagline)<p class="text-[10px] text-slate-500 mt-1">{{ $tagline }}</p>@endif
        <div class="my-2 border-b border-dashed border-slate-300"></div>
        <p class="font-bold text-slate-700">INVOICE RECEIPT</p>
        <p class="text-[10px] text-slate-500 mt-1">#INV-2026-001</p>
        <div class="mt-2 border-b border-dashed border-slate-300"></div>
    </div>
@elseif($templateStyle === 'executive')
    <div class="p-8 mb-6 border border-slate-200 bg-gradient-to-b from-slate-50 to-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1" style="background: linear-gradient(90deg, {{ $primaryColor }}, {{ $accentColor }});"></div>
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-800 tracking-wide uppercase">Acme Corporation</h2>
                @if($showTagline && $tagline)<p class="text-xs text-slate-500 mt-1">{{ $tagline }}</p>@endif
            </div>
            <div class="text-right">
                <h1 class="text-xl font-light text-slate-400 uppercase tracking-[0.2em]">Invoice</h1>
            </div>
        </div>
    </div>
@endif
