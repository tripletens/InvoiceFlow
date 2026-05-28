<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-teal-500 border border-transparent rounded-xl font-bold text-xs text-slate-950 uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 shadow-lg transition-all']) }}>
    {{ $slot }}
</button>
