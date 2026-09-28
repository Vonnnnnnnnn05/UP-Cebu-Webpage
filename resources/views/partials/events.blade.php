<section id="events" class="bg-cream-bg py-16 sm:py-22 border-b border-border-card scroll-mt-24 lg:scroll-mt-28">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="reveal-on-scroll mb-10">
            <div class="flex items-center gap-2.5 text-[11px] font-bold tracking-[1.5px] uppercase text-green-base mb-2">
                <span class="inline-block w-6 sm:w-7 h-0.5 bg-green-base rounded-full"></span>
                CALENDAR &amp; ENGAGEMENTS
            </div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-maroon-base tracking-tight leading-tight">
                        Upcoming Events &amp; Workshops
                    </h2>
                    <p class="max-w-xl text-xs sm:text-[13px] leading-relaxed text-ink-muted mt-2">
                        Engage in university pitch competitions, IP clinics, innovation masterclasses, and regional summits hosted across UP Cebu campuses.
                    </p>
                </div>
                <a href="#contact"
                    class="inline-flex items-center gap-2 text-xs font-bold text-maroon-base hover:text-green-base transition-colors group self-start md:self-auto py-1">
                    <span>Request a Workshop for Your College</span>
                    <svg class="w-3.5 h-3.5 text-gold-base transition-transform group-hover:translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>

        @if ($featuredEvent)
        <!-- Featured Summit Card (Full Width Hero Card) -->
        <div class="bg-white border-2 border-gold-base/60 rounded-2xl p-6 sm:p-8 lg:p-10 shadow-sm mb-8 reveal-on-scroll">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <div class="lg:col-span-8">
                    <div class="flex flex-wrap items-center gap-2.5 mb-3">
                        <span class="bg-green-base text-white text-[10px] font-bold tracking-wider uppercase px-3 py-0.5 rounded-full">
                            {{ $featuredEvent->badge_label ?? 'FEATURED EVENT' }}
                        </span>
                        <span class="bg-gold-light text-gold-deep text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full">
                            {{ strtoupper($featuredEvent->venue_type ?? 'EVENT') }}
                        </span>
                        <span class="text-xs text-ink-muted flex items-center gap-1.5 font-medium">
                            <svg class="w-3.5 h-3.5 text-gold-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            {{ $featuredEvent->start_time }} – {{ $featuredEvent->end_time }}
                        </span>
                    </div>

                    <h3 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-tight mb-3">
                        {{ $featuredEvent->title }}
                    </h3>

                    <p class="text-xs sm:text-[13px] leading-relaxed text-ink-muted mb-4 max-w-2xl">
                        {{ $featuredEvent->summary }}
                    </p>

                    <div class="flex items-center gap-2 text-xs font-semibold text-ink-base">
                        <svg class="w-4 h-4 text-maroon-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span>{{ $featuredEvent->venue }}</span>
                    </div>
                </div>

                <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end justify-between gap-4 border-t lg:border-t-0 lg:border-l border-border-card pt-4 lg:pt-0 lg:pl-8">
                    <!-- Prominent Date Badge -->
                    <div class="text-left lg:text-right">
                        <span class="text-3xl sm:text-4xl font-serif font-bold text-maroon-base block leading-none">
                            {{ $featuredEvent->event_date ? $featuredEvent->event_date->format('d') : '' }}
                        </span>
                        <span class="text-xs font-bold text-green-base uppercase tracking-widest block mt-1">
                            {{ $featuredEvent->event_date ? $featuredEvent->event_date->format('F Y') : '' }}
                        </span>
                    </div>

                    <a href="{{ $featuredEvent->registration_url ?? '#contact' }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white hover:bg-maroon-base text-maroon-base hover:text-white border-2 border-maroon-base text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95 w-full sm:w-auto justify-center group">
                        <span>Register for Summit</span>
                        <svg class="w-3.5 h-3.5 text-gold-deep group-hover:text-gold-light transition-all group-hover:translate-x-0.5 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Upcoming Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($upcomingEvents as $index => $event)
            <div
                class="bg-white border border-border-card rounded-xl p-6 shadow-xs hover:shadow-md transition-all flex flex-col justify-between reveal-on-scroll stagger-{{ $index + 1 }}">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="bg-green-base/10 text-green-base text-[9.5px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-full">
                            {{ $event->category }}
                        </span>
                        <div class="text-right">
                            <span class="font-serif text-lg font-bold text-maroon-base leading-none block">
                                {{ $event->event_date ? $event->event_date->format('M d') : '' }}
                            </span>
                        </div>
                    </div>

                    <h4 class="font-serif text-[17px] font-bold text-maroon-base leading-snug mb-2">
                        {{ $event->title }}
                    </h4>

                    <p class="text-[12px] leading-relaxed text-ink-muted mb-4 line-clamp-3">
                        {{ $event->summary }}
                    </p>
                </div>

                <div class="border-t border-border-card/60 pt-3 flex flex-col gap-2">
                    <div class="text-[11px] text-ink-muted flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gold-deep shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        <span>{{ $event->start_time }} – {{ $event->end_time }}</span>
                    </div>

                    <div class="text-[11px] text-ink-muted flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-maroon-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        <span class="truncate">{{ $event->venue }}</span>
                    </div>

                    <a href="{{ $event->registration_url ?? '#contact' }}"
                        class="inline-flex items-center gap-1 text-xs font-bold text-maroon-base hover:text-green-base transition-colors mt-1">
                        <span>Details &amp; Sign-Up</span>
                        <svg class="w-3 h-3 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 text-center text-xs text-ink-muted bg-white rounded-xl border border-border-card">
                No additional upcoming events scheduled at this moment.
            </div>
            @endforelse
        </div>
    </div>
</section>
