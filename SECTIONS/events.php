<?php
/**
 * Dynamic Calendar & Events Section
 * UP Cebu TTBDO
 */
// Ensure database connection is available
if (!isset($conn)) {
    require_once __DIR__ . '/../ADDITIONALS/db_connect.php';
}

// Fetch featured event
$featured_event = db_fetch_one($conn, "SELECT * FROM events WHERE is_featured = 1 AND status != 'cancelled' ORDER BY event_date ASC LIMIT 1");

// Fetch upcoming events (excluding featured)
$upcoming_events = db_fetch_all($conn, "SELECT * FROM events WHERE status != 'cancelled' AND is_featured = 0 ORDER BY event_date ASC LIMIT 3");

// Fallback if no featured item was explicitly marked
if (!$featured_event && !empty($upcoming_events)) {
    $featured_event = array_shift($upcoming_events);
}
?>
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

        <?php if ($featured_event): ?>
        <!-- Featured Summit Card (Full Width Hero Card) -->
        <div class="bg-white border-2 border-gold-base/60 rounded-2xl p-6 sm:p-8 lg:p-10 shadow-sm mb-8 reveal-on-scroll">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <div class="lg:col-span-8">
                    <div class="flex flex-wrap items-center gap-2.5 mb-3">
                        <span class="bg-green-base text-white text-[10px] font-bold tracking-wider uppercase px-3 py-0.5 rounded-full">
                            <?= e($featured_event['badge_label'] ?? 'FEATURED EVENT') ?>
                        </span>
                        <span class="bg-gold-light text-gold-deep text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full">
                            <?= strtoupper(e($featured_event['venue_type'] ?? 'EVENT')) ?>
                        </span>
                        <span class="text-[11.5px] font-semibold text-maroon-base">
                            <?= date('F j, Y', strtotime($featured_event['event_date'])) ?> • <?= e($featured_event['start_time']) ?> – <?= e($featured_event['end_time']) ?> PHT
                        </span>
                    </div>

                    <h3 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-snug mb-3">
                        <?= e($featured_event['title']) ?>
                    </h3>

                    <p class="text-xs sm:text-[13px] leading-relaxed text-ink-muted mb-4">
                        <?= e($featured_event['summary']) ?>
                    </p>

                    <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-xs text-ink-base">
                        <span class="flex items-center gap-1.5 font-medium">
                            <svg class="w-4 h-4 text-green-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                            </svg>
                            <?= e($featured_event['venue']) ?>
                        </span>
                        <?php if ($featured_event['venue_type'] === 'hybrid' || $featured_event['venue_type'] === 'virtual'): ?>
                        <span class="flex items-center gap-1.5 font-medium">
                            <svg class="w-4 h-4 text-gold-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                            Online / Live Stream Broadcast Available
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3 justify-center">
                    <a href="<?= e($featured_event['registration_url'] ?: '#contact') ?>"
                        class="inline-flex items-center justify-center gap-2 bg-maroon-base hover:bg-maroon-hover text-white text-xs sm:text-[13px] font-bold px-6 py-3.5 rounded-lg shadow-md hover:-translate-y-0.5 active:scale-95 transition-all text-center">
                        <span>Register to Attend (Free)</span>
                        <svg class="w-4 h-4 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                    <a href="#contact"
                        class="inline-flex items-center justify-center gap-2 bg-cream-soft hover:bg-cream-bg border border-border-card text-ink-base text-xs font-semibold px-5 py-3 rounded-lg transition-colors text-center">
                        <svg class="w-4 h-4 text-ink-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                        </svg>
                        <span>Inquire About Event</span>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Upcoming Schedule Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php 
            $stagger = 1;
            if (!empty($upcoming_events)):
                foreach ($upcoming_events as $event):
            ?>
            <article
                class="bg-white border border-border-card rounded-xl p-6 shadow-xs hover:border-maroon-base/40 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between reveal-on-scroll stagger-<?= $stagger++ ?>">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="bg-maroon-base text-white text-[9px] font-bold px-2.5 py-0.5 rounded uppercase tracking-wider">
                            <?= e($event['category']) ?>
                        </span>
                        <span class="text-[11px] font-semibold text-green-base">
                            <?= strtoupper(date('M d, Y', strtotime($event['event_date']))) ?>
                        </span>
                    </div>

                    <h4 class="font-serif text-[16px] font-bold text-maroon-base leading-snug mb-2">
                        <?= e($event['title']) ?>
                    </h4>

                    <p class="text-[12px] leading-relaxed text-ink-muted mb-4 line-clamp-3">
                        <?= e($event['summary']) ?>
                    </p>

                    <div class="text-[11px] text-ink-muted space-y-1 mb-5">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gold-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span><?= e($event['start_time']) ?> – <?= e($event['end_time']) ?> PHT</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-green-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                            </svg>
                            <span class="truncate"><?= e($event['venue']) ?></span>
                        </div>
                    </div>
                </div>

                <a href="<?= e($event['registration_url'] ?: '#contact') ?>"
                    class="inline-flex items-center justify-between w-full pt-3 border-t border-border-card text-xs font-bold text-maroon-base hover:text-green-base transition-colors group">
                    <span>Reserve a Seat / Register</span>
                    <svg class="w-3.5 h-3.5 text-gold-base group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </article>
            <?php 
                endforeach;
            else:
            ?>
            <div class="col-span-3 bg-white border border-border-card rounded-xl p-8 text-center text-ink-muted text-xs">
                No upcoming events scheduled at this moment. Check back soon!
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
