<section id="home"
    class="relative min-h-[520px] sm:min-h-[500px] md:min-h-[540px] lg:min-h-[580px] xl:min-h-[620px] 2xl:min-h-[660px] overflow-hidden bg-[#7B1113] text-white flex items-center scroll-mt-24 lg:scroll-mt-28">

    <style>
        .hero-campus-photo {
            background-image: url('{{ asset('assets/hero-campus.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center 8%;
        }
        @media (min-width: 640px) {
            .hero-campus-photo {
                background-position: center 12%;
            }
        }
        @media (min-width: 1024px) {
            .hero-campus-photo {
                background-position: center 16%;
            }
        }
        @media (min-width: 1536px) {
            .hero-campus-photo {
                background-position: center 18%;
            }
        }
    </style>

    <!-- Background photograph: Positioned cleanly on the right half on desktop and wide monitors so the Oblation is fully in frame, unclipped, and unobstructed -->
    <div class="absolute inset-y-0 right-0 w-full sm:w-[70%] md:w-[64%] lg:w-[58%] xl:w-[55%] 2xl:w-[52%] max-w-[1440px] overflow-hidden z-0 pointer-events-none">
        <div class="w-full h-full hero-campus-photo transform origin-top transition-transform duration-300"
            role="img"
            aria-label="UP Cebu campus landscape and iconic Oblation statue"></div>

        <!-- Left edge smooth blend into the UP Maroon background (desktop) -->
        <div class="hidden sm:block absolute inset-y-0 left-0 w-32 md:w-48 lg:w-64 bg-gradient-to-r from-[#7B1113] via-[#7B1113]/75 via-35% to-transparent z-10 pointer-events-none"></div>

        <!-- Top subtle vignette for clean header transition -->
        <div class="absolute top-0 inset-x-0 h-16 bg-gradient-to-b from-[#7B1113]/50 to-transparent z-10 pointer-events-none"></div>

        <!-- Bottom subtle blend -->
        <div class="absolute bottom-0 inset-x-0 h-12 bg-gradient-to-t from-[#7B1113]/40 to-transparent z-10 pointer-events-none"></div>
    </div>

    <!-- Left text protection backdrop -->
    <!-- Mobile: vertical bottom gradient for readability -->
    <div class="sm:hidden absolute inset-0 z-10 bg-gradient-to-t from-[#7B1113] via-[#7B1113]/90 via-55% to-transparent pointer-events-none"></div>
    <!-- Desktop: left-to-right gradient that ensures text contrast and stops safely before the Oblation -->
    <div class="hidden sm:block absolute inset-y-0 left-0 w-full sm:w-[54%] md:w-[50%] lg:w-[46%] z-10 bg-gradient-to-r from-[#7B1113] via-[#7B1113]/95 via-75% to-transparent pointer-events-none"></div>

    <!-- Hero Content -->
    <div
        class="relative z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-10 sm:pt-14 sm:pb-16 md:py-18 w-full reveal-on-scroll">
        <!-- Academic Green UP Cebu TTBDO badge -->
        <div class="mb-3.5">
            <span
                class="inline-flex items-center gap-1.5 bg-green-base text-white border border-green-light/40 px-3 py-1 rounded-full text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider shadow-xs">
                UP Cebu TTBDO
            </span>
        </div>

        <!-- Headline: Clean Unified White -->
        <h1
            class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-[54px] font-bold leading-[1.06] tracking-tight my-2.5 sm:my-3 text-white drop-shadow-xs">
            Your Idea,<br>
            <span class="text-white">Our Dedication.</span>
        </h1>

        <p
            class="max-w-md sm:max-w-lg text-xs sm:text-[13px] leading-relaxed text-white/90 mb-6 drop-shadow-xs">
            The Technology Transfer and Business Development Office (TTBDO) connects the University’s
            research and innovation with industry, community, and society for a more sustainable and inclusive
            future.
        </p>

        <!-- Hero CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto relative z-30">
            <a class="inline-flex items-center justify-center gap-2.5 bg-gold-base text-maroon-base border border-gold-base font-bold text-xs sm:text-[13px] px-6 py-3 rounded-lg shadow-md hover:bg-maroon-base hover:text-white hover:border-gold-base active:scale-95 transition-all duration-200 group"
                href="#programs">
                <span>Explore Our Programs</span>
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
            <a class="inline-flex items-center justify-center gap-2 bg-white/15 text-white hover:bg-white/25 border border-white/40 font-semibold text-xs sm:text-[13px] px-5 py-3 rounded-lg shadow-xs active:scale-95 transition-all duration-200"
                href="#events">
                <span>Upcoming Events</span>
            </a>
        </div>
    </div>

    <!-- Decorative multi-band diagonal accent -->
    <svg class="absolute z-20 bottom-0 right-0 w-36 sm:w-64 md:w-80 lg:w-[460px] h-auto pointer-events-none drop-shadow-lg hidden sm:block opacity-85 sm:opacity-100"
        viewBox="0 0 460 140" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <polygon points="120,140 460,21 460,28 140,140" fill="#D4A017" />
        <polygon points="140,140 460,28 460,63 240,140" fill="#7B1113" />
        <polygon points="240,140 460,63 460,70 260,140" fill="#D4A017" />
        <polygon points="260,140 460,70 460,140" fill="#146B3A" />
    </svg>
</section>
