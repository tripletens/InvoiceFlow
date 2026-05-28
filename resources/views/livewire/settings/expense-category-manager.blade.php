<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-white">Expense Categories</h1>
            <p class="text-slate-400 text-sm mt-1">Manage standard and custom categories for classifying your company expenditures.</p>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid md:grid-cols-12 gap-8">
            {{-- Add Category Form --}}
            <div class="md:col-span-5 rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-6 self-start">
                <div>
                    <h2 class="text-lg font-bold text-white">Add Custom Category</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Create your own business-specific classification.</p>
                </div>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">Category Name</label>
                        <input wire:model="newCategoryName" type="text" placeholder="e.g. Subcontractors" 
                            class="w-full px-3 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/40" />
                        @error('newCategoryName') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-950 font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-cyan-500/10">
                        Create Category
                    </button>
                </form>
            </div>

            {{-- Categories List --}}
            <div class="md:col-span-7 rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-white">Categories Ledger</h2>
                    <p class="text-xs text-slate-400 mt-0.5">View and manage default and custom categories.</p>
                </div>

                <div class="space-y-3">
                    {{-- Default Categories --}}
                    @foreach($defaultCategories as $cat)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-950/40 border border-slate-800/80">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">🏷️</span>
                                <div>
                                    <p class="font-semibold text-slate-200 text-sm">{{ $cat }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">System Default</p>
                                </div>
                            </div>
                            <span class="text-slate-600 text-sm" title="System categories cannot be deleted">🔒</span>
                        </div>
                    @endforeach

                    {{-- Custom Categories --}}
                    @if($customCategories->isEmpty())
                        <div class="p-6 border border-dashed border-slate-800 rounded-xl text-center text-slate-500 text-xs">
                            No custom categories added yet.
                        </div>
                    @else
                        @foreach($customCategories as $category)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-800/40 border border-slate-700/40 hover:border-slate-750 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg">🏷️</span>
                                    <div>
                                        <p class="font-semibold text-white text-sm">{{ $category->name }}</p>
                                        <p class="text-[10px] text-cyan-400 font-bold uppercase tracking-wider">Custom Category</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="delete({{ $category->id }})" 
                                    class="p-2 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-all text-xs"
                                    title="Delete category (moves existing expenses to 'Other')">
                                    🗑️ Delete
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
