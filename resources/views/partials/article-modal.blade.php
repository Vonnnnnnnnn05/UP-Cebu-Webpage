<div id="article-modal" class="fixed inset-0 z-[110] bg-black/65 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 opacity-0 pointer-events-none transition-all duration-300" role="dialog" aria-modal="true" aria-labelledby="article-modal-title">
    <div class="article-dialog-card w-full max-w-3xl bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-border-card overflow-hidden flex flex-col max-h-[90vh] transform scale-95 transition-all duration-300">

        <!-- Header Bar -->
        <div class="p-4 sm:p-5 border-b border-border-card/80 bg-cream-soft flex items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <span id="article-modal-badge" class="bg-maroon-base text-white text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full">
                    FEATURED STORY
                </span>
                <span id="article-modal-category" class="bg-green-base/10 text-green-base text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full">
                    PARTNERSHIP
                </span>
                <span id="article-modal-date" class="text-[11px] text-ink-muted">
                    Date
                </span>
            </div>

            <button id="article-modal-close" type="button" aria-label="Close article viewer"
                class="p-1.5 rounded-lg text-ink-muted hover:text-maroon-base hover:bg-black/5 transition-colors cursor-pointer shrink-0">
                <kbd class="hidden sm:inline-block text-[10px] font-mono px-1.5 py-0.5 rounded bg-white border border-border-card text-ink-muted shadow-2xs mr-1">ESC</kbd>
                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Scrollable Article Body -->
        <div class="p-6 sm:p-8 overflow-y-auto space-y-6">
            <!-- Article Title & Author -->
            <div>
                <h2 id="article-modal-title" class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-snug tracking-tight">
                    Article Title
                </h2>
                <div class="flex items-center gap-2 mt-2.5 text-xs text-ink-muted">
                    <span class="inline-block w-2 h-2 rounded-full bg-gold-base"></span>
                    <span>By <strong id="article-modal-author" class="font-semibold text-ink-base">TTBDO Media Communications</strong></span>
                </div>
            </div>

            <!-- Metrics Bar -->
            <div id="article-modal-metrics" class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3.5 bg-cream-soft rounded-xl border border-border-card text-center hidden">
                <div id="article-metric-startups-container" class="hidden">
                    <span id="article-modal-startups" class="block text-base sm:text-lg font-bold text-maroon-base"></span>
                    <span class="text-[10px] text-ink-muted uppercase font-semibold">Startups Assisted</span>
                </div>
                <div id="article-metric-meeting-container" class="hidden">
                    <span id="article-modal-meeting" class="block text-base sm:text-lg font-bold text-maroon-base"></span>
                    <span class="text-[10px] text-ink-muted uppercase font-semibold">Meeting Focus</span>
                </div>
                <div id="article-metric-region-container" class="hidden">
                    <span id="article-modal-region" class="block text-base sm:text-lg font-bold text-maroon-base"></span>
                    <span class="text-[10px] text-ink-muted uppercase font-semibold">Partner / Region</span>
                </div>
            </div>

            <!-- Lead Summary Callout -->
            <div class="p-4 sm:p-5 rounded-xl bg-cream-soft border-l-4 border-gold-base text-xs sm:text-sm font-medium text-ink-base leading-relaxed">
                <p id="article-modal-summary" class="m-0"></p>
            </div>

            <!-- Full Article Content -->
            <div id="article-modal-content" class="text-xs sm:text-sm leading-relaxed text-ink-base space-y-3.5 border-t border-border-card/50 pt-5">
            </div>
        </div>

        <!-- Footer Bar -->
        <div class="p-4 sm:p-5 bg-cream-soft border-t border-border-card/80 flex flex-wrap items-center justify-between gap-3 text-xs text-ink-muted">
            <span class="text-[11px] hidden sm:inline">Technology Transfer &amp; Business Development Office &bull; UP Cebu</span>
            <div class="flex items-center gap-2.5 ml-auto">
                <button type="button" id="article-modal-share-btn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-border-card hover:bg-cream-bg text-ink-base font-semibold text-xs transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                    </svg>
                    <span>Copy Link</span>
                </button>
                <button type="button" onclick="closeArticleModal()"
                    class="px-4 py-1.5 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-xs font-bold transition-colors cursor-pointer">
                    Close
                </button>
            </div>
        </div>

    </div>
</div>
