<div id="search-modal" class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm flex items-start justify-center pt-12 sm:pt-20 px-3 sm:px-4" role="dialog" aria-modal="true" aria-labelledby="search-modal-input">
    <div class="search-dialog-card w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-border-card overflow-hidden flex flex-col max-h-[85vh]">
        <!-- Search Input Header -->
        <div class="p-3.5 sm:p-4 border-b border-border-card/80 flex items-center gap-3 bg-cream-soft">
            <div class="w-9 h-9 rounded-xl bg-maroon-base/10 text-maroon-base flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-maroon-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
            <input id="search-modal-input" type="search" placeholder="Search programs, news, IP, events, office location..." autocomplete="off"
                class="flex-1 bg-transparent text-ink-base placeholder:text-ink-muted/70 text-sm sm:text-base focus:outline-none font-medium" />
            <button id="search-modal-close" type="button" aria-label="Close search"
                class="p-1.5 rounded-lg text-ink-muted hover:text-maroon-base hover:bg-black/5 transition-colors cursor-pointer">
                <kbd class="hidden sm:inline-block text-[10px] font-mono px-1.5 py-0.5 rounded bg-white border border-border-card text-ink-muted shadow-2xs mr-1">ESC</kbd>
                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Quick Filter Chips -->
        <div class="px-3.5 sm:px-4 py-2 border-b border-border-card/60 bg-white flex items-center gap-1.5 overflow-x-auto text-[11px] font-medium text-ink-muted">
            <span class="text-[10px] uppercase font-bold text-ink-muted/60 tracking-wider mr-1 shrink-0">Filter:</span>
            <button type="button" data-filter="all" class="search-filter-chip px-2.5 py-1 rounded-full bg-maroon-base text-white hover:opacity-95 transition-all shrink-0 cursor-pointer">All</button>
            <button type="button" data-filter="Programs" class="search-filter-chip px-2.5 py-1 rounded-full bg-cream-bg text-ink-base hover:bg-gold-base/20 transition-all shrink-0 cursor-pointer">Programs</button>
            <button type="button" data-filter="News" class="search-filter-chip px-2.5 py-1 rounded-full bg-cream-bg text-ink-base hover:bg-gold-base/20 transition-all shrink-0 cursor-pointer">News</button>
            <button type="button" data-filter="Events" class="search-filter-chip px-2.5 py-1 rounded-full bg-cream-bg text-ink-base hover:bg-gold-base/20 transition-all shrink-0 cursor-pointer">Events</button>
            <button type="button" data-filter="About Us" class="search-filter-chip px-2.5 py-1 rounded-full bg-cream-bg text-ink-base hover:bg-gold-base/20 transition-all shrink-0 cursor-pointer">About</button>
            <button type="button" data-filter="Contact" class="search-filter-chip px-2.5 py-1 rounded-full bg-cream-bg text-ink-base hover:bg-gold-base/20 transition-all shrink-0 cursor-pointer">Location</button>
        </div>

        <!-- Search Results Scroll Area -->
        <div id="search-results-list" class="p-3 sm:p-4 overflow-y-auto space-y-1.5 min-h-[160px] max-h-[50vh]">
            <!-- Populated dynamically via JS -->
        </div>

        <!-- Search Modal Footer -->
        <div class="px-4 py-2.5 bg-cream-soft border-t border-border-card/80 flex items-center justify-between text-[11px] text-ink-muted">
            <span id="search-results-count" class="font-medium">Loading search index...</span>
            <div class="flex items-center gap-3 text-[10px] hidden sm:flex">
                <span><kbd class="px-1 py-0.5 bg-white border border-border-card rounded shadow-2xs">↑</kbd> <kbd class="px-1 py-0.5 bg-white border border-border-card rounded shadow-2xs">↓</kbd> Navigate</span>
                <span><kbd class="px-1 py-0.5 bg-white border border-border-card rounded shadow-2xs">↵</kbd> Select</span>
                <span><kbd class="px-1 py-0.5 bg-white border border-border-card rounded shadow-2xs">ESC</kbd> Close</span>
            </div>
        </div>
    </div>
</div>
