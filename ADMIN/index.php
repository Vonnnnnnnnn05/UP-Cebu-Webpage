<?php
/**
 * Admin Dashboard
 * UP Cebu TTBDO
 */
$page_title = 'Dashboard';
require_once __DIR__ . '/layout_header.php';

// Fetch statistics
$total_news       = db_fetch_one($conn, "SELECT COUNT(*) as c FROM news")['c'] ?? 0;
$total_events     = db_fetch_one($conn, "SELECT COUNT(*) as c FROM events WHERE status != 'cancelled'")['c'] ?? 0;
$total_inquiries  = db_fetch_one($conn, "SELECT COUNT(*) as c FROM inquiries")['c'] ?? 0;
$pending_inq      = db_fetch_one($conn, "SELECT COUNT(*) as c FROM inquiries WHERE status = 'pending'")['c'] ?? 0;

// Fetch 5 most recent inquiries
$recent_inquiries = db_fetch_all($conn, "SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5");

// Fetch 3 most recent news
$recent_news_list = db_fetch_all($conn, "SELECT id, title, category, is_featured, published_date FROM news ORDER BY published_date DESC LIMIT 3");

// Fetch 3 upcoming events
$upcoming_events_list = db_fetch_all($conn, "SELECT id, title, event_date, venue, status FROM events WHERE status != 'cancelled' ORDER BY event_date ASC LIMIT 3");
?>

<!-- Welcome Banner -->
<div class="mb-8 bg-white border border-border-card rounded-2xl p-6 sm:p-7 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold tracking-[1.5px] uppercase text-green-base mb-1.5">
            <span class="inline-block w-2.5 h-2.5 bg-green-base rounded-full"></span>
            SYSTEM OVERVIEW
        </div>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-tight">
            Welcome back, <?= e($current_admin['full_name']) ?>
        </h1>
        <p class="text-sm text-ink-muted mt-1.5 leading-relaxed max-w-2xl">
            Logged in as <strong class="text-ink-base"><?= e($current_admin['email']) ?></strong> (Role: <?= ucfirst(e($current_admin['role'])) ?>). Manage your public announcements, innovation calendar, and incoming consultation requests below.
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
        <a href="manage-news.php?action=new"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-sm font-bold transition-all shadow-xs">
            <svg class="w-4 h-4 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Post News</span>
        </a>
        <a href="manage-events.php?action=new"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-green-base hover:bg-green-hover text-white text-sm font-bold transition-all shadow-xs">
            <svg class="w-4 h-4 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Schedule Event</span>
        </a>
    </div>
</div>

<!-- Metrics Overview Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Card 1: News -->
    <div class="bg-white border border-border-card rounded-xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider block">News &amp; Articles</span>
            <span class="text-3xl sm:text-4xl font-bold font-serif text-maroon-base mt-1 block"><?= $total_news ?></span>
            <a href="manage-news.php" class="text-xs text-maroon-base hover:text-green-base font-semibold transition-colors mt-2 inline-block">Manage articles &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-maroon-base/10 text-maroon-base flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
            </svg>
        </div>
    </div>

    <!-- Card 2: Events -->
    <div class="bg-white border border-border-card rounded-xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider block">Active Events</span>
            <span class="text-3xl sm:text-4xl font-bold font-serif text-green-base mt-1 block"><?= $total_events ?></span>
            <a href="manage-events.php" class="text-xs text-green-base hover:text-maroon-base font-semibold transition-colors mt-2 inline-block">View schedule &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-green-base/10 text-green-base flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
        </div>
    </div>

    <!-- Card 3: Total Inquiries -->
    <div class="bg-white border border-border-card rounded-xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider block">Total Inquiries</span>
            <span class="text-3xl sm:text-4xl font-bold font-serif text-ink-base mt-1 block"><?= $total_inquiries ?></span>
            <a href="manage-inquiries.php" class="text-xs text-ink-muted hover:text-ink-base font-semibold transition-colors mt-2 inline-block">All submissions &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
        </div>
    </div>

    <!-- Card 4: Pending Action -->
    <div class="bg-white border-2 border-gold-base/50 rounded-xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-maroon-base uppercase tracking-wider block">Pending Inquiries</span>
            <span class="text-3xl sm:text-4xl font-bold font-serif text-maroon-base mt-1 block"><?= $pending_inq ?></span>
            <a href="manage-inquiries.php?status=pending" class="text-xs text-maroon-base hover:text-green-base font-bold transition-colors mt-2 inline-block">Review pending &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gold-light text-gold-deep flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 8.25h.008v.008H12v-.008Z" />
            </svg>
        </div>
    </div>
</div>

<!-- Two Column Layout: Recent Inquiries & Content Preview -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left Column: Recent Inquiries (Span 7) -->
    <div class="lg:col-span-7 bg-white border border-border-card rounded-2xl p-6 shadow-xs">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-border-card">
            <div>
                <h3 class="font-serif text-xl font-bold text-maroon-base">Recent Inquiries &amp; Consultations</h3>
                <span class="text-xs text-ink-muted">Inbound messages from students, faculty, and industry</span>
            </div>
            <a href="manage-inquiries.php" class="text-sm font-bold text-maroon-base hover:text-green-base transition-colors">
                View All &rarr;
            </a>
        </div>

        <?php if (!empty($recent_inquiries)): ?>
        <div class="divide-y divide-border-card/60">
            <?php foreach ($recent_inquiries as $inq): ?>
            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2.5 mb-1">
                        <span class="font-bold text-sm text-ink-base"><?= e($inq['full_name']) ?></span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold 
                            <?= $inq['status'] === 'pending' ? 'bg-amber-100 text-amber-800' : ($inq['status'] === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') ?>">
                            <?= strtoupper(str_replace('_', ' ', e($inq['status']))) ?>
                        </span>
                        <span class="text-xs text-ink-muted hidden sm:inline"><?= date('M j, Y', strtotime($inq['created_at'])) ?></span>
                    </div>
                    <strong class="block text-sm text-maroon-base truncate font-semibold"><?= e($inq['subject']) ?></strong>
                    <p class="text-xs text-ink-muted truncate mt-0.5"><?= e($inq['message']) ?></p>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <a href="manage-inquiries.php?id=<?= $inq['id'] ?>"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-border-card hover:border-maroon-base text-ink-base hover:text-maroon-base transition-colors">
                        Details
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="py-8 text-center text-sm text-ink-muted">
            No inquiries received yet.
        </div>
        <?php endif; ?>
    </div>

    <!-- Right Column: Content Summary (Span 5) -->
    <div class="lg:col-span-5 space-y-6">
        <!-- News Widget -->
        <div class="bg-white border border-border-card rounded-2xl p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-border-card">
                <h3 class="font-serif text-lg font-bold text-maroon-base">Recent News Headlines</h3>
                <a href="manage-news.php" class="text-sm font-bold text-maroon-base hover:text-green-base transition-colors">Manage &rarr;</a>
            </div>
            <div class="space-y-3.5">
                <?php foreach ($recent_news_list as $n): ?>
                <div class="flex items-start justify-between gap-3 text-sm">
                    <div class="min-w-0">
                        <span class="text-xs uppercase font-bold text-green-base block mb-0.5"><?= e($n['category']) ?></span>
                        <a href="manage-news.php?edit=<?= $n['id'] ?>" class="font-semibold text-ink-base hover:text-maroon-base transition-colors block line-clamp-1">
                            <?= e($n['title']) ?>
                        </a>
                    </div>
                    <span class="text-xs text-ink-muted shrink-0 font-medium"><?= date('M d', strtotime($n['published_date'])) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Upcoming Events Widget -->
        <div class="bg-white border border-border-card rounded-2xl p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-border-card">
                <h3 class="font-serif text-lg font-bold text-maroon-base">Upcoming Events</h3>
                <a href="manage-events.php" class="text-sm font-bold text-maroon-base hover:text-green-base transition-colors">Manage &rarr;</a>
            </div>
            <div class="space-y-3.5">
                <?php foreach ($upcoming_events_list as $ev): ?>
                <div class="flex items-start justify-between gap-3 text-sm">
                    <div class="min-w-0">
                        <span class="text-xs uppercase font-bold text-gold-deep block mb-0.5"><?= date('M j, Y', strtotime($ev['event_date'])) ?></span>
                        <a href="manage-events.php?edit=<?= $ev['id'] ?>" class="font-semibold text-ink-base hover:text-green-base transition-colors block line-clamp-1">
                            <?= e($ev['title']) ?>
                        </a>
                        <span class="text-xs text-ink-muted block truncate mt-0.5"><?= e($ev['venue']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
