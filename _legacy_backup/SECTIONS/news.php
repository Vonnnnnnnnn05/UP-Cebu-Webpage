<?php
/**
 * Dynamic News & Announcements Section
 * UP Cebu TTBDO
 */
// Ensure database connection is available
if (!isset($conn)) {
    require_once __DIR__ . '/../ADDITIONALS/db_connect.php';
}

// Fetch featured news item
$featured_news = db_fetch_one($conn, "SELECT * FROM news WHERE is_featured = 1 AND is_published = 1 ORDER BY published_date DESC LIMIT 1");

// Fetch recent articles (excluding featured)
$recent_news = db_fetch_all($conn, "SELECT * FROM news WHERE is_published = 1 AND is_featured = 0 ORDER BY published_date DESC LIMIT 3");

// Fallback if no featured item was explicitly marked
if (!$featured_news && !empty($recent_news)) {
    $featured_news = array_shift($recent_news);
}

// Fetch all published articles for client-side modal reader
$all_news_modal_data = db_fetch_all($conn, "SELECT id, title, category, badge_label, published_date, author, summary, content, startups_supported, meeting_focus, coverage_area FROM news WHERE is_published = 1 ORDER BY published_date DESC");
?>
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
            <?php if ($featured_news): ?>
            <!-- Featured Article (Span 7) -->
            <article
                class="lg:col-span-7 bg-white border border-border-card rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between reveal-on-scroll stagger-1 group">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap items-center gap-2.5 mb-4">
                        <span class="bg-maroon-base text-white text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full">
                            <?= e($featured_news['badge_label'] ?? 'FEATURED STORY') ?>
                        </span>
                        <span class="bg-green-base/10 text-green-base text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full">
                            <?= e($featured_news['category']) ?>
                        </span>
                        <span class="text-[11px] text-ink-muted">
                            <?= date('F j, Y', strtotime($featured_news['published_date'])) ?>
                        </span>
                    </div>

                    <h3 onclick="openArticleModal(<?= (int)$featured_news['id'] ?>)"
                        class="font-serif text-xl sm:text-2xl font-bold text-maroon-base leading-snug group-hover:text-green-base transition-colors mb-3 cursor-pointer">
                        <?= e($featured_news['title']) ?>
                    </h3>

                    <p class="text-xs sm:text-[13px] leading-relaxed text-ink-muted mb-6">
                        <?= e($featured_news['summary']) ?>
                    </p>

                    <?php if (!empty($featured_news['startups_supported']) || !empty($featured_news['meeting_focus']) || !empty($featured_news['coverage_area'])): ?>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3.5 bg-cream-soft rounded-xl border border-border-card text-center mb-6">
                        <?php if (!empty($featured_news['startups_supported'])): ?>
                        <div>
                            <span class="block text-base sm:text-lg font-bold text-maroon-base"><?= e($featured_news['startups_supported']) ?></span>
                            <span class="text-[10px] text-ink-muted uppercase font-semibold">Startups Assisted</span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($featured_news['meeting_focus'])): ?>
                        <div>
                            <span class="block text-base sm:text-lg font-bold text-maroon-base"><?= e($featured_news['meeting_focus']) ?></span>
                            <span class="text-[10px] text-ink-muted uppercase font-semibold">Meeting Focus</span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($featured_news['coverage_area'])): ?>
                        <div class="col-span-2 sm:col-span-1">
                            <span class="block text-base sm:text-lg font-bold text-maroon-base"><?= e($featured_news['coverage_area']) ?></span>
                            <span class="text-[10px] text-ink-muted uppercase font-semibold">Partner / Region</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="px-6 sm:px-8 pb-6 sm:pb-8 pt-2 border-t border-border-card/60 flex items-center justify-between">
                    <span class="text-[11px] font-medium text-ink-muted">By <?= e($featured_news['author']) ?></span>
                    <button type="button" onclick="openArticleModal(<?= (int)$featured_news['id'] ?>)"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-maroon-base hover:text-green-base transition-colors group/link cursor-pointer">
                        <span>View Article</span>
                        <svg class="w-3.5 h-3.5 text-gold-base transition-transform group-hover/link:translate-x-1"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </article>
            <?php endif; ?>

            <!-- Recent News List (Span 5) -->
            <div class="lg:col-span-5 flex flex-col gap-4">
                <?php 
                $stagger = 2;
                if (!empty($recent_news)):
                    foreach ($recent_news as $news_item): 
                ?>
                <article
                    onclick="openArticleModal(<?= (int)$news_item['id'] ?>)"
                    class="bg-white border border-border-card rounded-xl p-5 shadow-xs hover:border-maroon-base/40 hover:-translate-y-0.5 transition-all duration-200 reveal-on-scroll stagger-<?= $stagger++ ?> cursor-pointer group/item flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="inline-block bg-gold-light text-gold-deep text-[9.5px] font-bold px-2 py-0.5 rounded tracking-wider uppercase">
                                <?= e($news_item['category']) ?>
                            </span>
                            <span class="text-[10.5px] text-ink-muted">
                                <?= strtoupper(date('M d, Y', strtotime($news_item['published_date']))) ?>
                            </span>
                        </div>
                        <h4 class="font-serif text-[15px] font-bold text-maroon-base leading-snug group-hover/item:text-green-base transition-colors mb-1.5">
                            <?= e($news_item['title']) ?>
                        </h4>
                        <p class="text-[11.5px] text-ink-muted leading-relaxed line-clamp-2 mb-3">
                            <?= e($news_item['summary']) ?>
                        </p>
                    </div>
                    <div class="flex items-center justify-between pt-2.5 border-t border-border-card/40 text-[11px] font-bold text-maroon-base group-hover/item:text-green-base transition-colors">
                        <span>View Article</span>
                        <svg class="w-3.5 h-3.5 text-gold-base transition-transform group-hover/item:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </article>
                <?php 
                    endforeach;
                else: 
                ?>
                <div class="bg-white border border-border-card rounded-xl p-6 text-center text-ink-muted text-xs">
                    No recent announcements posted.
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- News Data Store for Instant Modal Reading -->
        <script>
            window.TTBDO_NEWS_STORE = <?= json_encode($all_news_modal_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        </script>

        <!-- Forms & Policies Quick Access Bar -->
        <div class="mt-8 bg-white border border-border-card rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs reveal-on-scroll">
            <div class="flex items-center gap-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-lg bg-green-base/10 text-green-base flex items-center justify-center shrink-0 hidden sm:flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-bold text-maroon-base">Essential University Forms &amp; Policies</span>
                    <span class="text-[11px] text-ink-muted">Download official Technology Disclosure Forms (TDF) and UP System IP guidelines</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <a href="#contact"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-md bg-cream-soft hover:bg-cream-bg border border-border-card text-xs font-semibold text-ink-base transition-colors">
                    <svg class="w-3.5 h-3.5 text-green-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Download TDF</span>
                </a>
                <a href="#contact"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-md bg-maroon-base hover:bg-maroon-hover text-white text-xs font-bold transition-colors">
                    <span>IP Policy Guide</span>
                </a>
            </div>
        </div>
    </div>
</section>
