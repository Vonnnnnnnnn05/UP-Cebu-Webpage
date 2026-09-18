<header class="bg-maroon-dark text-white border-b-2 border-gold-base sticky top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 sm:h-20 lg:h-[100px] flex items-center gap-3 sm:gap-4 lg:gap-6">

        <!-- Logo / Brand -->
        <a href="#home" class="flex items-center gap-2.5 sm:gap-3.5 group shrink-0">
            <div class="bg-white rounded-md px-2 py-1 shadow-xs border border-white/40 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                <img src="{{ asset('assets/ttbdo-full-logo.png') }}" alt="UP Cebu TTBDO Logo" class="h-8 sm:h-9 lg:h-10 w-auto object-contain shrink-0" />
            </div>

            <div class="leading-tight shrink-0 whitespace-nowrap">
                <span class="block font-serif text-[12px] xs:text-[13px] sm:text-[15px] md:text-[16.5px] lg:text-[18px] xl:text-[19.5px] font-bold text-white leading-[1.15] tracking-tight group-hover:text-gold-base transition-colors">
                    <span class="block">University of the</span>
                    <span class="block">Philippines Cebu</span>
                </span>
            </div>
        </a>

        <!-- Right-aligned Header Group: Desktop Navigation Sections + Search -->
        <div class="hidden lg:flex items-center gap-3 xl:gap-5 2xl:gap-6 h-full ml-auto shrink-0">
            <nav class="flex items-center gap-4 xl:gap-6 2xl:gap-7 h-full text-[13.5px] xl:text-[14.5px] font-semibold whitespace-nowrap" aria-label="Main navigation">
                <a class="nav-link active whitespace-nowrap shrink-0" href="#home">
                    Home
                </a>
                <a class="nav-link whitespace-nowrap shrink-0" href="#news">
                    News
                </a>

                <!-- Programs Dropdown Box -->
                <div class="relative group/dropdown h-full flex items-center">
                    <a class="nav-link whitespace-nowrap shrink-0 flex items-center gap-1.5" href="#programs">
                        <span>Programs</span>
                        <svg class="w-3.5 h-3.5 text-white/75 group-hover/dropdown:text-gold-base transition-transform duration-200 group-hover/dropdown:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </a>
                    <!-- Dropdown Box Panel (Centered under Programs) -->
                    <div class="absolute top-full left-1/2 -translate-x-1/2 pt-2 opacity-0 invisible group-hover/dropdown:opacity-100 group-hover/dropdown:visible transition-all duration-200 pointer-events-none group-hover/dropdown:pointer-events-auto z-50">
                        <div class="dropdown-panel-box bg-maroon-dark border border-gold-base/40 hover:border-gold-base/70 rounded-xl shadow-2xl p-2.5 w-[295px] max-w-[calc(100vw-2rem)] backdrop-blur-md transition-colors">
                            <div class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-gold-base border-b border-white/10 mb-1 flex items-center justify-between">
                                <span>Programs &amp; Services</span>
                                <span class="text-[9px] text-cream-bg/60 font-normal">Containers</span>
                            </div>
                            <a href="#program-ip" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">IP Rights Protection</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Patents, Trademarks &amp; Copyrights</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <a href="#program-incubation" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">Business Incubation</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Co-working &amp; Startup Mentorship</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <a href="#program-simp" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">Student Mentorship (SIMP)</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Prototype Grants &amp; Pitch Training</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <a href="#program-licensing" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">Technology Licensing</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Commercialization &amp; Valuation</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <a href="#program-msme" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">MSME Mentorship</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Appropriate Tech &amp; DOST SETUP</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <a href="#program-internship" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">Internship &amp; IP Certification</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Patent Docketing &amp; Agent Mentorship</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <a class="nav-link whitespace-nowrap shrink-0" href="#events">
                    Events
                </a>

                <!-- About Us Dropdown Box -->
                <div class="relative group/dropdown h-full flex items-center">
                    <a class="nav-link whitespace-nowrap shrink-0 flex items-center gap-1.5" href="#about">
                        <span>About&nbsp;Us</span>
                        <svg class="w-3.5 h-3.5 text-white/75 group-hover/dropdown:text-gold-base transition-transform duration-200 group-hover/dropdown:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </a>
                    <div class="absolute top-full right-0 pt-2 opacity-0 invisible group-hover/dropdown:opacity-100 group-hover/dropdown:visible transition-all duration-200 pointer-events-none group-hover/dropdown:pointer-events-auto z-50">
                        <div class="dropdown-panel-box bg-maroon-dark border border-gold-base/40 hover:border-gold-base/70 rounded-xl shadow-2xl p-2.5 w-[295px] max-w-[calc(100vw-2rem)] backdrop-blur-md transition-colors">
                            <div class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-gold-base border-b border-white/10 mb-1 flex items-center justify-between">
                                <span>About UP Cebu TTBDO</span>
                                <span class="text-[9px] text-cream-bg/60 font-normal">Containers</span>
                            </div>
                            <a href="#about-overview" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">Overview &amp; Mandate</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Institutional Role &amp; History</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <a href="#about-vision" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">Vision &amp; Mission</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Core Purpose &amp; Aspirations</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <a href="#about-pillars" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">Four Strategic Pillars</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Governance, Licensing &amp; Incubation</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <a href="#about-impact" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">Impact &amp; Statistics</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">Filings, Startups &amp; Metrics</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <a href="#about-office" class="dropdown-link-item flex items-center justify-between px-3 py-2 rounded-lg text-white group/item">
                                <div class="flex flex-col">
                                    <span class="dropdown-item-title text-xs font-semibold text-white transition-colors">Office &amp; Directory</span>
                                    <span class="dropdown-item-desc text-[10px] text-cream-bg/70 transition-colors">3rd Flr, Technology Innovation Center</span>
                                </div>
                                <svg class="dropdown-item-arrow w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 text-gold-base shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <a class="nav-link whitespace-nowrap shrink-0" href="#contact">
                    Contact
                </a>

                @if(Auth::guard('admin')->check())
                <a class="nav-link whitespace-nowrap shrink-0 flex items-center gap-1.5 text-gold-base font-bold" href="{{ route('admin.dashboard') }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                    <span>Dashboard</span>
                </a>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline m-0 p-0 flex items-center h-full">
                    @csrf
                    <button type="submit" class="nav-link whitespace-nowrap shrink-0 cursor-pointer bg-transparent border-0 text-white/90 hover:text-gold-base font-semibold">
                        Logout
                    </button>
                </form>
                @else
                <a class="nav-link whitespace-nowrap shrink-0 hover:text-gold-base" href="{{ route('admin.login') }}">
                    Login
                </a>
                @endif
            </nav>

            <!-- Search Quick Button (Desktop) -->
            <button id="search-toggle-btn" type="button" aria-label="Open search modal"
                class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium transition-colors cursor-pointer group shrink-0">
                <svg class="w-3.5 h-3.5 text-gold-base transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <span class="hidden xl:inline text-[11px] text-cream-bg/90">Search...</span>
                <kbd class="hidden xl:inline-block text-[9px] font-mono px-1 py-0.5 rounded bg-white/15 border border-white/20 text-cream-bg/80">Ctrl K</kbd>
            </button>
        </div>

        <!-- Mobile Controls (Search Button + Hamburger) -->
        <div class="flex lg:hidden items-center gap-2 ml-auto shrink-0">
            <!-- Mobile Search Icon Button -->
            <button id="mobile-search-toggle-btn" type="button" aria-label="Open search dialog"
                class="p-2 rounded-md bg-white/10 hover:bg-white/20 text-white transition-colors flex items-center justify-center cursor-pointer">
                <svg class="w-5 h-5 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>

            <!-- Mobile Hamburger Menu Button -->
            <button id="menu-toggle" type="button"
                class="p-2 rounded-md bg-white/10 hover:bg-white/20 text-white focus:outline-none transition-colors shrink-0"
                aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobile-menu">
                <!-- Hamburger open icon -->
                <svg id="menu-icon-open" class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <!-- Close icon -->
                <svg id="menu-icon-close" class="w-6 h-6 stroke-current hidden" fill="none" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

    </div>

    <!-- Mobile Navigation Dropdown Panel -->
    <div id="mobile-menu"
        class="hidden lg:hidden bg-maroon-dark/95 backdrop-blur-md border-b-2 border-gold-base px-4 py-4 flex-col gap-1 text-sm font-medium transition-all shadow-xl">
        <a class="mobile-nav-link active px-3 py-2.5 rounded-md flex items-center justify-between" href="#home">
            <span>Home</span>
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
        <a class="mobile-nav-link px-3 py-2.5 rounded-md flex items-center justify-between" href="#news">
            <span>News</span>
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
        <a class="mobile-nav-link px-3 py-2.5 rounded-md flex items-center justify-between" href="#programs">
            <span>Programs</span>
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
        <a class="mobile-nav-link px-3 py-2.5 rounded-md flex items-center justify-between" href="#events">
            <span>Events</span>
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
        <a class="mobile-nav-link px-3 py-2.5 rounded-md flex items-center justify-between" href="#about">
            <span>About Us</span>
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
        <a class="mobile-nav-link px-3 py-2.5 rounded-md flex items-center justify-between" href="#contact">
            <span>Contact</span>
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>

        @if(Auth::guard('admin')->check())
        <div class="mt-2 pt-2 border-t border-white/15 space-y-1">
            <a class="mobile-nav-link px-3 py-2.5 rounded-md flex items-center justify-between text-gold-base font-bold text-sm"
                href="{{ route('admin.dashboard') }}">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                    <span>Dashboard</span>
                </span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" class="m-0 p-0">
                @csrf
                <button type="submit" class="w-full mobile-nav-link px-3 py-2.5 rounded-md flex items-center justify-between text-sm text-left text-white/90 hover:text-gold-base cursor-pointer bg-transparent border-0">
                    <span>Logout</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                </button>
            </form>
        </div>
        @else
        <div class="mt-2 pt-2 border-t border-white/15">
            <a class="mobile-nav-link flex items-center justify-between py-2.5 px-3 rounded-md text-sm whitespace-nowrap"
                href="{{ route('admin.login') }}">
                <span>Login</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>
        @endif
    </div>
</header>
