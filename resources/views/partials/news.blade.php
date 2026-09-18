<section id="news" class="bg-cream-bg py-14 sm:py-20 border-b border-border-card scroll-mt-24 lg:scroll-mt-28">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="reveal-on-scroll mb-10">
            <div class="flex items-center gap-2.5 text-[11px] font-bold tracking-[1.5px] uppercase text-green-base mb-2">
                <span class="inline-block w-6 sm:w-7 h-0.5 bg-green-base rounded-full"></span>
                NEWS &amp; ANNOUNCEMENTS
            </div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-maroon-base tracking-tight leading-tight">
                        Latest News &amp; Research Highlights
                    </h2>
                    <p class="max-w-xl text-xs sm:text-[13px] leading-relaxed text-ink-muted mt-2">
                        Discover university inventions, grant calls, regional innovation partnerships, and technology transfer milestones across UP Cebu.
                    </p>
                </div>
                <a href="#contact"
                    class="inline-flex items-center gap-2 text-xs font-bold text-maroon-base hover:text-green-base transition-colors group self-start md:self-auto py-1">
                    <span>Inquire for Media &amp; Press</span>
                    <svg class="w-3.5 h-3.5 text-gold-base transition-transform group-hover:translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Featured News + Recent Articles Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            @if ($featuredNews)
            <!-- Featured Article (Span 7) -->
            <article
                class="lg:col-span-7 bg-white border border-border-card rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between reveal-on-scroll stagger-1 group">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap items-center gap-2.5 mb-4">
                        <span class="bg-maroon-base text-white text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full">
                            {{ $featuredNews->badge_label ?? 'FEATURED STORY' }}
                        </span>
                        <span class="bg-green-base/10 text-green-base text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full">
                            {{ $featuredNews->category }}
                        </span>
                        <span class="text-[11px] text-ink-muted">
                            {{ $featuredNews->published_date ? $featuredNews->published_date->format('F j, Y') : '' }}
                        </span>
                    </div>

                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-maroon-base leading-snug group-hover:text-maroon-hover transition-colors mb-3">
                        <a href="javascript:void(0)" onclick="openArticleModal({{ $featuredNews->id }})" class="hover:underline">
                            {{ $featuredNews->title }}
                        </a>
                    </h3>

                    <p class="text-xs sm:text-[13px] leading-relaxed text-ink-base mb-6">
                        {{ $featuredNews->summary }}
                    </p>

                    @if ($featuredNews->startups_supported || $featuredNews->meeting_focus || $featuredNews->coverage_area)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3.5 bg-cream-soft rounded-xl border border-border-card text-center mb-6">
                        @if($featuredNews->startups_supported)
                        <div>
                            <span class="block text-base sm:text-lg font-bold text-maroon-base">{{ $featuredNews->startups_supported }}</span>
                            <span class="text-[10px] text-ink-muted uppercase font-semibold">Startups Assisted</span>
                        </div>
                        @endif
                        @if($featuredNews->meeting_focus)
                        <div>
                            <span class="block text-base sm:text-lg font-bold text-maroon-base">{{ $featuredNews->meeting_focus }}</span>
                            <span class="text-[10px] text-ink-muted uppercase font-semibold">Meeting Focus</span>
                        </div>
                        @endif
                        @if($featuredNews->coverage_area)
                        <div>
                            <span class="block text-base sm:text-lg font-bold text-maroon-base">{{ $featuredNews->coverage_area }}</span>
                            <span class="text-[10px] text-ink-muted uppercase font-semibold">Partner / Region</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="px-6 py-4 sm:px-8 sm:py-4 bg-cream-soft border-t border-border-card flex items-center justify-between mt-auto">
                    <span class="text-[11px] text-ink-muted font-medium">
                        By <strong class="text-ink-base font-semibold">{{ $featuredNews->author }}</strong>
                    </span>
                    <button type="button" onclick="openArticleModal({{ $featuredNews->id }})"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-maroon-base hover:text-green-base transition-colors cursor-pointer">
                        <span>Read Full Story</span>
                        <svg class="w-3.5 h-3.5 text-gold-base transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </article>
            @endif

            <!-- Recent News Column (Span 5) -->
            <div class="lg:col-span-5 flex flex-col gap-4">
                @forelse ($recentNews as $index => $item)
                <article
                    class="bg-white border border-border-card rounded-xl p-5 sm:p-6 shadow-xs hover:shadow-md transition-all flex flex-col justify-between reveal-on-scroll stagger-{{ $index + 2 }} group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2.5">
                            <span class="bg-green-base/10 text-green-base text-[9.5px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-full">
                                {{ $item->category }}
                            </span>
                            <span class="text-[10.5px] text-ink-muted">
                                {{ $item->published_date ? $item->published_date->format('M j, Y') : '' }}
                            </span>
                        </div>

                        <h4 class="font-serif text-base font-bold text-maroon-base leading-snug group-hover:text-maroon-hover transition-colors mb-2">
                            <a href="javascript:void(0)" onclick="openArticleModal({{ $item->id }})" class="hover:underline">
                                {{ $item->title }}
                            </a>
                        </h4>

                        <p class="text-[11.5px] leading-relaxed text-ink-muted line-clamp-2 mb-4">
                            {{ $item->summary }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between border-t border-border-card/60 pt-3">
                        <span class="text-[10.5px] text-ink-muted">
                            {{ $item->author }}
                        </span>
                        <button type="button" onclick="openArticleModal({{ $item->id }})"
                            class="inline-flex items-center gap-1 text-[11px] font-bold text-maroon-base hover:text-green-base transition-colors cursor-pointer">
                            <span>Read</span>
                            <svg class="w-3 h-3 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </article>
                @empty
                <div class="p-6 text-center text-xs text-ink-muted bg-white rounded-xl border border-border-card">
                    No recent articles found.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- Inject News Database for Client-Side Article Reader Modal -->
<script>
    window.TTBDO_NEWS_STORE = @json($allNewsModalData);
</script>
