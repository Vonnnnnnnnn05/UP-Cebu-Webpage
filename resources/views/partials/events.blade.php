<section id="events" class="bg-cream-bg py-10 sm:py-14 border-b border-border-card scroll-mt-24 lg:scroll-mt-28">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="reveal-on-scroll mb-5">
            <div class="flex items-center gap-2.5 text-[11px] font-bold tracking-[1.5px] uppercase text-green-base mb-1.5">
                <span class="inline-block w-5 sm:w-6 h-0.5 bg-green-base rounded-full"></span>
                ACTIVITIES &amp; CALENDAR
            </div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
                <div>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base tracking-tight leading-tight">
                        TTBDO Activities Calendar
                    </h2>
                    <p class="max-w-2xl text-xs sm:text-[12.5px] leading-relaxed text-ink-muted mt-1.5">
                        Explore our ongoing visits, stakeholder meetings, incubation activities, seminars, ceremonies, and university initiatives. Browse current, upcoming, or past historical activities below.
                    </p>
                </div>
                <button type="button" onclick="openBookingModal()"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-maroon-base hover:text-green-base transition-colors group self-start md:self-auto py-1 cursor-pointer">
                    <span>Inquire / Book Activity</span>
                    <svg class="w-3.5 h-3.5 text-gold-base transition-transform group-hover:translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- ======================================================
             MONTHLY CALENDAR CONTROLS & FILTERING
        ======================================================= -->
        <div class="reveal-on-scroll" id="events-calendar-root">

            <!-- Top Filter & Month/Year Navigation Bar -->
            <div class="bg-white border border-border-card rounded-xl p-2.5 sm:p-3 shadow-2xs mb-3 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-2.5">

                <!-- Month / Year Selector & Prev/Next Buttons (Single neat row on mobile) -->
                <div class="flex items-center justify-between sm:justify-start gap-1.5 w-full lg:w-auto">
                    <button id="cal-prev" type="button" aria-label="Previous month"
                        class="inline-flex items-center justify-center p-2 sm:px-2.5 sm:py-1.5 rounded-lg bg-cream-soft border border-border-card text-xs font-bold text-ink-base hover:bg-maroon-base hover:text-white transition-all shadow-2xs cursor-pointer shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                        <span class="hidden sm:inline text-[11px] ml-1">Prev</span>
                    </button>

                    <!-- Month Dropdown -->
                    <select id="cal-month-select" aria-label="Select month"
                        class="flex-1 sm:flex-initial px-2 py-1.5 rounded-lg bg-cream-soft border border-border-card text-xs font-bold text-maroon-base focus:bg-white focus:outline-none focus:border-maroon-base transition-all cursor-pointer min-w-0">
                        <option value="0">January</option>
                        <option value="1">February</option>
                        <option value="2">March</option>
                        <option value="3">April</option>
                        <option value="4">May</option>
                        <option value="5">June</option>
                        <option value="6">July</option>
                        <option value="7">August</option>
                        <option value="8">September</option>
                        <option value="9">October</option>
                        <option value="10">November</option>
                        <option value="11">December</option>
                    </select>

                    <!-- Year Dropdown -->
                    <select id="cal-year-select" aria-label="Select year"
                        class="w-20 sm:w-auto px-2 py-1.5 rounded-lg bg-cream-soft border border-border-card text-xs font-bold text-maroon-base focus:bg-white focus:outline-none focus:border-maroon-base transition-all cursor-pointer shrink-0">
                        <!-- Populated by JS -->
                    </select>

                    <button id="cal-next" type="button" aria-label="Next month"
                        class="inline-flex items-center justify-center p-2 sm:px-2.5 sm:py-1.5 rounded-lg bg-cream-soft border border-border-card text-xs font-bold text-ink-base hover:bg-maroon-base hover:text-white transition-all shadow-2xs cursor-pointer shrink-0">
                        <span class="hidden sm:inline text-[11px] mr-1">Next</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>

                    <button id="cal-today" type="button"
                        class="px-2.5 py-1.5 rounded-lg bg-white border border-border-card text-[11px] font-bold text-ink-muted hover:bg-maroon-base hover:text-white transition-all shrink-0 cursor-pointer">
                        Today
                    </button>
                </div>

                <!-- Category & Status Filters (Clean stack or side-by-side) -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 pt-2 lg:pt-0 border-t lg:border-t-0 border-border-card/60 w-full lg:w-auto">
                    <!-- Category Select -->
                    <div class="flex items-center gap-1.5 flex-1 min-w-0">
                        <label for="cal-category-select" class="text-[10px] font-bold text-ink-muted uppercase tracking-wider hidden sm:inline shrink-0">
                            Category:
                        </label>
                        <select id="cal-category-select"
                            class="w-full px-2.5 py-1.5 rounded-lg bg-cream-soft border border-border-card text-xs font-semibold text-ink-base focus:bg-white focus:outline-none focus:border-maroon-base transition-all cursor-pointer">
                            <option value="all">All Categories</option>
                            <option value="Activity">Activity</option>
                            <option value="Meeting">Meeting</option>
                            <option value="Incubation">Incubation</option>
                            <option value="University Visit">University Visit</option>
                            <option value="Training">Training</option>
                            <option value="Seminar">Seminar</option>
                            <option value="Ceremony">Ceremony</option>
                            <option value="Partnership">Partnership</option>
                            <option value="Workshop">Workshop</option>
                        </select>
                    </div>

                    <!-- Status Filter Toggle Buttons (Equally distributed grid on mobile, inline on desktop) -->
                    <div class="grid grid-cols-3 sm:flex items-center bg-cream-soft p-0.5 rounded-lg border border-border-card shrink-0">
                        <button data-cal-filter="all" type="button"
                            class="cal-filter-btn active text-center px-2 py-1 rounded text-[10.5px] font-bold bg-maroon-base text-white transition-all cursor-pointer">
                            All
                        </button>
                        <button data-cal-filter="upcoming" type="button"
                            class="cal-filter-btn text-center px-2 py-1 rounded text-[10.5px] font-bold text-ink-muted hover:text-ink-base transition-all cursor-pointer">
                            Upcoming
                        </button>
                        <button data-cal-filter="completed" type="button"
                            class="cal-filter-btn text-center px-2 py-1 rounded text-[10.5px] font-bold text-ink-muted hover:text-ink-base transition-all cursor-pointer">
                            Past
                        </button>
                    </div>
                </div>

            </div>

            <!-- Calendar Container Card -->
            <div class="bg-white border border-border-card rounded-xl shadow-xs overflow-hidden">
                <!-- Day-of-week headers -->
                <div class="grid grid-cols-7 border-b border-border-card bg-cream-soft">
                    @foreach (['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $dow)
                    <div class="py-1.5 sm:py-2 text-center text-[9px] sm:text-[10.5px] font-bold uppercase tracking-wider text-ink-muted">
                        <span class="hidden sm:inline">{{ $dow }}</span>
                        <span class="sm:hidden">{{ substr($dow, 0, 3) }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Calendar 7-column grid — injected dynamically by JS -->
                <div id="cal-grid" class="grid grid-cols-7"></div>
            </div>

            <!-- Calendar Helper Caption -->
            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-[11px] text-ink-muted px-1">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-maroon-base"></span>
                        <span>Today</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-md bg-blue-50 border border-blue-200"></span>
                        <span>Activity / Meeting</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="text-maroon-base font-bold underline">View Details</span>
                        <span>= Detailed documentation available</span>
                    </span>
                </div>
                <div class="text-[10px] text-ink-muted/80">
                    * Multi-day events appear cleanly on their Start Date.
                </div>
            </div>

            <!-- ======================================================
                 ACTIVITY REGISTRATION & BOOKING BANNER
            ======================================================= -->
            <div class="mt-6 bg-white border border-border-card rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4 reveal-on-scroll">
                <div class="space-y-1 max-w-xl">
                    <div class="inline-flex items-center gap-2 text-[10px] font-bold tracking-[1.5px] uppercase text-green-base">
                        <span class="inline-block w-4 h-0.5 bg-green-base rounded-full"></span>
                        EVENT BOOKING &amp; CONSULTATION
                    </div>
                    <h3 class="font-serif text-lg sm:text-xl font-bold text-maroon-base leading-tight">
                        Looking to Book an Activity or Consultation?
                    </h3>
                    <p class="text-xs sm:text-[12.5px] text-ink-muted leading-relaxed">
                        Register an upcoming stakeholder activity, book incubation facilities, or schedule an innovation meeting with the UP Cebu TTBDO team.
                    </p>
                </div>
                <button type="button" onclick="openBookingModal()"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-maroon-base hover:bg-maroon-dark text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all cursor-pointer w-full md:w-auto shrink-0 group">
                    <svg class="w-4 h-4 text-gold-base transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>Register / Book an Activity</span>
                </button>
            </div>

        </div>
    </div>
</section>

<!-- ======================================================
     EVENT QUICK-DETAIL SIDE PANEL (Slide-in)
======================================================= -->
<div id="cal-event-panel"
    class="fixed inset-y-0 right-0 z-[200] w-full max-w-[340px] sm:max-w-[360px] bg-white shadow-2xl border-l border-border-card transform translate-x-full transition-transform duration-300 flex flex-col"
    role="dialog" aria-modal="true" aria-label="Event Details">
    <div class="flex items-center justify-between px-4 py-3 border-b border-border-card bg-cream-soft shrink-0">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-maroon-base"></span>
            <span class="text-xs font-bold uppercase tracking-wider text-maroon-base">Activity Overview</span>
        </div>
        <button id="cal-panel-close" type="button" aria-label="Close event details"
            class="p-1 rounded-lg hover:bg-border-card/60 transition-colors cursor-pointer text-ink-muted hover:text-ink-base">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div id="cal-panel-body" class="overflow-y-auto flex-1 px-4 py-4 space-y-3 text-xs text-ink-base">
        <!-- Injected by openPanel(ev) -->
    </div>
</div>
<div id="cal-panel-backdrop" class="hidden fixed inset-0 z-[199] bg-black/40 backdrop-blur-xs transition-opacity" aria-hidden="true"></div>

@push('scripts')
<script>
(function () {
    'use strict';

    // ── Server Events Data ──────────────────────────────────────────────────
    const RAW_EVENTS = @json($calendarEventsData);
    const MONTH_NAMES = [
        'January','February','March','April','May','June',
        'July','August','September','October','November','December'
    ];

    // ── State ───────────────────────────────────────────────────────────────
    const today = new Date();
    let curYear  = today.getFullYear();
    let curMonth = today.getMonth(); // 0-indexed (0=Jan)
    let activeFilter   = 'all';     // 'all' | 'upcoming' | 'completed'
    let activeCategory = 'all';     // 'all' or category string

    // ── DOM References ──────────────────────────────────────────────────────
    const grid           = document.getElementById('cal-grid');
    const monthSelect    = document.getElementById('cal-month-select');
    const yearSelect     = document.getElementById('cal-year-select');
    const categorySelect = document.getElementById('cal-category-select');
    const prevBtn        = document.getElementById('cal-prev');
    const nextBtn        = document.getElementById('cal-next');
    const todayBtn       = document.getElementById('cal-today');
    const panel          = document.getElementById('cal-event-panel');
    const panelBody      = document.getElementById('cal-panel-body');
    const panelClose     = document.getElementById('cal-panel-close');
    const backdrop       = document.getElementById('cal-panel-backdrop');

    // ── Helper functions ────────────────────────────────────────────────────
    function pad(n) {
        return String(n).padStart(2, '0');
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Populate Year dropdown (from min event year or current-2 up to current+5)
    function initYearSelect() {
        const yearsSet = new Set([curYear - 2, curYear - 1, curYear, curYear + 1, curYear + 2]);
        RAW_EVENTS.forEach(e => {
            if (e.event_date) {
                const y = parseInt(e.event_date.split('-')[0], 10);
                if (!isNaN(y)) yearsSet.add(y);
            }
        });

        const sortedYears = Array.from(yearsSet).sort((a, b) => a - b);
        yearSelect.innerHTML = '';
        sortedYears.forEach(y => {
            const opt = document.createElement('option');
            opt.value = y;
            opt.textContent = y;
            if (y === curYear) opt.selected = true;
            yearSelect.appendChild(opt);
        });
    }

    // Check if category select contains all categories present in data
    function initCategorySelect() {
        const existingOpts = new Set();
        Array.from(categorySelect.options).forEach(opt => existingOpts.add(opt.value.toLowerCase()));

        RAW_EVENTS.forEach(e => {
            if (e.category && !existingOpts.has(e.category.toLowerCase())) {
                const opt = document.createElement('option');
                opt.value = e.category;
                opt.textContent = e.category;
                categorySelect.appendChild(opt);
                existingOpts.add(e.category.toLowerCase());
            }
        });
    }

    // Filter events for a given calendar date (YYYY-MM-DD)
    // NOTE (CRITICAL RULE): Event ONLY appears on its START DATE (event_date)!
    function eventsForDate(dateStr) {
        return RAW_EVENTS.filter(e => {
            if (!e.event_date) return false;
            // Only place on start date:
            if (e.event_date !== dateStr) return false;

            // Status filter
            if (activeFilter === 'upcoming' && e.status !== 'upcoming') return false;
            if (activeFilter === 'completed' && e.status !== 'completed') return false;

            // Category filter
            if (activeCategory !== 'all') {
                if (!e.category || e.category.toLowerCase() !== activeCategory.toLowerCase()) {
                    return false;
                }
            }

            return true;
        });
    }

    // Category styling badge classes
    function getCategoryStyle(cat) {
        const c = (cat || '').toLowerCase();
        if (c.includes('meeting')) {
            return 'bg-amber-100 text-amber-800 border-amber-200';
        } else if (c.includes('activity')) {
            return 'bg-sky-100 text-sky-800 border-sky-200';
        } else if (c.includes('incubation')) {
            return 'bg-purple-100 text-purple-800 border-purple-200';
        } else if (c.includes('visit')) {
            return 'bg-emerald-100 text-emerald-800 border-emerald-200';
        } else if (c.includes('ceremony') || c.includes('summit')) {
            return 'bg-rose-100 text-rose-800 border-rose-200';
        }
        return 'bg-gray-100 text-gray-700 border-gray-200';
    }

    function getDotStyle(cat) {
        const c = (cat || '').toLowerCase();
        if (c.includes('meeting')) return 'bg-amber-500';
        if (c.includes('activity')) return 'bg-sky-500';
        if (c.includes('incubation')) return 'bg-purple-500';
        if (c.includes('visit')) return 'bg-emerald-500';
        if (c.includes('ceremony') || c.includes('summit')) return 'bg-rose-500';
        return 'bg-maroon-base';
    }

    function getMobilePillStyle(cat) {
        const c = (cat || '').toLowerCase();
        if (c.includes('meeting')) {
            return 'bg-amber-50/90 text-amber-900 border border-amber-200/90 hover:bg-amber-100';
        } else if (c.includes('activity')) {
            return 'bg-sky-50/90 text-sky-900 border border-sky-200/90 hover:bg-sky-100';
        } else if (c.includes('incubation')) {
            return 'bg-purple-50/90 text-purple-900 border border-purple-200/90 hover:bg-purple-100';
        } else if (c.includes('visit')) {
            return 'bg-emerald-50/90 text-emerald-900 border border-emerald-200/90 hover:bg-emerald-100';
        } else if (c.includes('ceremony') || c.includes('summit')) {
            return 'bg-rose-50/90 text-rose-900 border border-rose-200/90 hover:bg-rose-100';
        }
        return 'bg-cream-soft text-ink-base border border-border-card/80 hover:bg-white';
    }

    // ── Calendar Rendering ──────────────────────────────────────────────────
    function render() {
        monthSelect.value = curMonth;
        yearSelect.value  = curYear;
        grid.innerHTML    = '';

        const firstDay = new Date(curYear, curMonth, 1).getDay(); // 0=Sun
        const daysInMonth = new Date(curYear, curMonth + 1, 0).getDate();
        const todayStr = `${today.getFullYear()}-${pad(today.getMonth() + 1)}-${pad(today.getDate())}`;

        // Blank leading cells before month starts
        for (let i = 0; i < firstDay; i++) {
            const blank = document.createElement('div');
            blank.className = 'min-h-[54px] sm:min-h-[78px] border-b border-r border-border-card/40 bg-cream-soft/30';
            grid.appendChild(blank);
        }

        // Days of month
        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = `${curYear}-${pad(curMonth + 1)}-${pad(d)}`;
            const evs     = eventsForDate(dateStr);
            const isToday = dateStr === todayStr;
            const col     = (firstDay + d - 1) % 7;
            const isLast  = col === 6;

            const cell = document.createElement('div');
            cell.className = [
                'min-h-[54px] sm:min-h-[78px] p-1 sm:p-1.5 border-b',
                isLast ? '' : 'border-r',
                'border-border-card/40 flex flex-col gap-0.5 sm:gap-1 transition-colors',
                isToday ? 'bg-maroon-base/5 ring-1 ring-inset ring-maroon-base/20' : 'bg-white hover:bg-cream-soft/40',
            ].join(' ');

            // Day Number Header
            const topBar = document.createElement('div');
            topBar.className = 'flex items-center justify-between leading-none';

            const dayNum = document.createElement('span');
            dayNum.className = [
                'text-[10px] sm:text-[11px] font-bold w-5 h-5 flex items-center justify-center rounded-full shrink-0',
                isToday ? 'bg-maroon-base text-white shadow-xs' : 'text-ink-muted',
            ].join(' ');
            dayNum.textContent = d;
            topBar.appendChild(dayNum);

            // Show count badge on mobile ONLY when there are multiple activities
            if (evs.length > 1) {
                const countBadge = document.createElement('span');
                countBadge.className = 'text-[7.5px] font-bold text-ink-muted/80 bg-cream-soft px-1 py-0.2 rounded-full border border-border-card/70 sm:hidden';
                countBadge.textContent = evs.length;
                countBadge.title = `${evs.length} activities on this day`;
                topBar.appendChild(countBadge);
            }

            cell.appendChild(topBar);

            if (evs.length > 0) {
                // Tapping anywhere on the date cell on mobile opens details for the first event
                cell.classList.add('cursor-pointer');
                cell.addEventListener('click', (e) => {
                    if (!e.target.closest('.event-clickable-action')) {
                        openPanel(evs[0]);
                    }
                });

                // --- MOBILE PRESENTATION (sm:hidden) ---
                // Sleek, compact 1-line pills with colored dot indicators
                const mobileContainer = document.createElement('div');
                mobileContainer.className = 'sm:hidden flex flex-col gap-0.5 w-full mt-0.5 overflow-hidden';

                evs.forEach(ev => {
                    const mobilePill = document.createElement('div');
                    mobilePill.className = `event-clickable-action flex items-center gap-1 w-full min-w-0 px-1 py-0.5 rounded text-[8px] font-bold ${getMobilePillStyle(ev.category)} cursor-pointer active:scale-95 transition-transform overflow-hidden`;
                    mobilePill.title = `${ev.category ? ev.category + ': ' : ''}${ev.title}`;
                    mobilePill.innerHTML = `
                        <span class="w-1.5 h-1.5 rounded-full shrink-0 ${getDotStyle(ev.category)}"></span>
                        <span class="truncate min-w-0 flex-1 leading-none">${escapeHtml(ev.title)}</span>
                    `;
                    mobilePill.addEventListener('click', (e) => {
                        e.stopPropagation();
                        openPanel(ev);
                    });
                    mobileContainer.appendChild(mobilePill);
                });
                cell.appendChild(mobileContainer);

                // --- DESKTOP PRESENTATION (hidden sm:flex) ---
                // Full cards with category badges and optional details link
                const desktopContainer = document.createElement('div');
                desktopContainer.className = 'hidden sm:flex flex-1 flex-col gap-1 overflow-hidden';

                evs.forEach(ev => {
                    const card = document.createElement('div');
                    card.className = 'event-clickable-action group text-left rounded-md p-1 sm:p-1.5 bg-cream-soft/60 hover:bg-white border border-border-card/70 hover:border-maroon-base/40 shadow-2xs hover:shadow-xs transition-all flex flex-col gap-0.5 cursor-pointer';

                    const catBadgeStyle = getCategoryStyle(ev.category);

                    let dateSpanText = '';
                    if (ev.end_date && ev.end_date !== ev.event_date) {
                        const startD = ev.event_date.split('-')[2];
                        const endD   = ev.end_date.split('-')[2];
                        dateSpanText = `${startD}–${endD}`;
                    }

                    card.innerHTML = `
                        <div class="flex items-center justify-between gap-1 leading-none">
                            <span class="inline-block text-[7.5px] sm:text-[8px] font-bold tracking-wider uppercase px-1 py-0.2 rounded border ${catBadgeStyle}">
                                ${escapeHtml(ev.category || 'Activity')}
                            </span>
                            ${dateSpanText ? `<span class="text-[7.5px] font-semibold text-ink-muted bg-white border border-border-card/60 px-1 py-0.2 rounded" title="Multi-day event">${dateSpanText}</span>` : ''}
                        </div>
                        <div class="text-[9.5px] sm:text-[10px] font-bold text-ink-base leading-tight group-hover:text-maroon-base transition-colors line-clamp-1 sm:line-clamp-2">
                            ${escapeHtml(ev.title)}
                        </div>
                        ${ev.show_view ? `
                            <div class="mt-0.5 pt-0.5 border-t border-border-card/40 flex items-center justify-between">
                                <a href="${ev.event_url}" class="event-clickable-action inline-flex items-center gap-0.5 text-[8px] sm:text-[8.5px] font-bold text-maroon-base hover:text-green-base transition-colors" onclick="event.stopPropagation();">
                                    <span>View Details</span>
                                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </div>
                        ` : ''}
                    `;

                    card.addEventListener('click', (e) => {
                        e.stopPropagation();
                        openPanel(ev);
                    });
                    desktopContainer.appendChild(card);
                });

                cell.appendChild(desktopContainer);
            }

            grid.appendChild(cell);
        }

        // Fill trailing blank cells to complete the grid row
        const total = firstDay + daysInMonth;
        const remainder = total % 7;
        if (remainder !== 0) {
            const fill = 7 - remainder;
            for (let i = 0; i < fill; i++) {
                const blank = document.createElement('div');
                blank.className = 'min-h-[54px] sm:min-h-[78px] border-b border-r border-border-card/40 bg-cream-soft/30 last:border-r-0';
                grid.appendChild(blank);
            }
        }
    }

    // ── Event Detail Side Panel ─────────────────────────────────────────────
    function openPanel(ev) {
        const catBadgeStyle = getCategoryStyle(ev.category);
        const statusColors = {
            upcoming:  'bg-green-100 text-green-800 border-green-200',
            completed: 'bg-gray-100 text-gray-700 border-gray-200',
            cancelled: 'bg-red-100 text-red-700 border-red-200',
        };

        const dateRangeDisplay = ev.formatted_date_range || ev.event_date;

        panelBody.innerHTML = `
            ${ev.image_url ? `
                <div class="mb-3 overflow-hidden rounded-xl border border-border-card bg-cream-soft">
                    <img src="${escapeHtml(ev.image_url)}" alt="${escapeHtml(ev.title)}" class="w-full h-36 object-cover">
                </div>
            ` : ''}

            <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                <span class="text-[9px] font-bold tracking-wider uppercase px-2 py-0.5 rounded border ${catBadgeStyle}">
                    ${escapeHtml(ev.category || 'Activity')}
                </span>
                <span class="text-[9px] font-bold tracking-wider uppercase px-1.5 py-0.5 rounded border ${statusColors[ev.status] || 'bg-gray-100 text-gray-600 border-gray-200'}">
                    ${(ev.status || '').toUpperCase()}
                </span>
                ${ev.badge_label ? `<span class="text-[8.5px] font-bold tracking-wider uppercase px-1.5 py-0.5 rounded-full bg-gold-light text-gold-deep border border-gold-base/30">${escapeHtml(ev.badge_label)}</span>` : ''}
            </div>

            <h3 class="font-serif text-base sm:text-lg font-bold text-maroon-base leading-snug">
                ${escapeHtml(ev.title)}
            </h3>

            <div class="bg-cream-soft border border-border-card/70 rounded-lg p-2.5 space-y-1.5 text-xs text-ink-muted mt-2">
                <div class="flex items-start gap-2">
                    <svg class="w-3.5 h-3.5 text-gold-deep shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                    <div>
                        <span class="font-bold text-ink-base block text-[11.5px]">${escapeHtml(dateRangeDisplay)}</span>
                        ${ev.end_date && ev.end_date !== ev.event_date ? '<span class="text-[9px] text-maroon-base font-semibold">Multi-day activity</span>' : '<span class="text-[9px] text-ink-muted">Single-day activity</span>'}
                    </div>
                </div>

                ${ev.venue ? `
                <div class="flex items-center gap-2 pt-1 border-t border-border-card/40 text-[11px]">
                    <svg class="w-3.5 h-3.5 text-maroon-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <span>${escapeHtml(ev.venue)}</span>
                </div>` : ''}
            </div>

            ${ev.summary ? `
            <div class="pt-2 text-xs text-ink-base/80 leading-relaxed border-t border-border-card/60">
                ${escapeHtml(ev.summary)}
            </div>` : ''}

            <!-- View Details Action -->
            <div class="pt-3 border-t border-border-card/60">
                ${ev.show_view ? `
                    <a href="${ev.event_url}"
                        class="inline-flex items-center justify-center gap-1.5 w-full px-4 py-2 rounded-lg bg-maroon-base hover:bg-maroon-dark text-white text-xs font-bold transition-all shadow-xs group">
                        <span>View Detailed Daily Documentation</span>
                        <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                ` : `
                    <div class="p-2.5 rounded-lg bg-cream-soft border border-border-card text-[10px] text-ink-muted text-center">
                        Simple activity entry (no detailed page required).
                    </div>
                `}
            </div>
        `;

        panel.classList.remove('translate-x-full');
        backdrop.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePanel() {
        panel.classList.add('translate-x-full');
        backdrop.classList.add('hidden');
        document.body.style.overflow = '';
    }

    panelClose.addEventListener('click', closePanel);
    backdrop.addEventListener('click', closePanel);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePanel(); });

    // ── Navigation & Filter Events ──────────────────────────────────────────
    prevBtn.addEventListener('click', () => {
        curMonth--;
        if (curMonth < 0) {
            curMonth = 11;
            curYear--;
        }
        render();
    });

    nextBtn.addEventListener('click', () => {
        curMonth++;
        if (curMonth > 11) {
            curMonth = 0;
            curYear++;
        }
        render();
    });

    todayBtn.addEventListener('click', () => {
        curYear  = today.getFullYear();
        curMonth = today.getMonth();
        render();
    });

    monthSelect.addEventListener('change', function () {
        curMonth = parseInt(this.value, 10);
        render();
    });

    yearSelect.addEventListener('change', function () {
        curYear = parseInt(this.value, 10);
        render();
    });

    categorySelect.addEventListener('change', function () {
        activeCategory = this.value;
        render();
    });

    // Status filter buttons
    document.querySelectorAll('.cal-filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            activeFilter = this.dataset.calFilter;
            document.querySelectorAll('.cal-filter-btn').forEach(b => {
                b.classList.remove('active', 'bg-maroon-base', 'text-white');
                b.classList.add('text-ink-muted');
            });
            this.classList.add('active', 'bg-maroon-base', 'text-white');
            this.classList.remove('text-ink-muted');
            render();
        });
    });

    // ── Initialization ──────────────────────────────────────────────────────
    initYearSelect();
    initCategorySelect();
    render();
})();
</script>
@endpush
