<?php
/**
 * News & Announcements Management (CRUD)
 * UP Cebu TTBDO Admin Portal
 */
$page_title = 'Manage News & Research';
require_once __DIR__ . '/layout_header.php';

$alert_type = '';
$alert_msg = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $deleted = db_execute($conn, "DELETE FROM news WHERE id = ?", "i", [$delete_id]);
    if ($deleted) {
        $alert_type = 'success';
        $alert_msg = 'News article deleted successfully.';
    } else {
        $alert_type = 'error';
        $alert_msg = 'Failed to delete article.';
    }
}

// Handle Featured Toggle
if (isset($_GET['toggle_featured'])) {
    $item_id = (int)$_GET['toggle_featured'];
    $current_state = (int)($_GET['state'] ?? 0);
    $new_state = $current_state === 1 ? 0 : 1;

    // If making this featured, un-feature others first
    if ($new_state === 1) {
        db_execute($conn, "UPDATE news SET is_featured = 0");
    }

    db_execute($conn, "UPDATE news SET is_featured = ? WHERE id = ?", "ii", [$new_state, $item_id]);
    $alert_type = 'success';
    $alert_msg = 'Featured status updated.';
}

// Handle Form Submission (Create or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_news'])) {
    $id                 = (int)($_POST['id'] ?? 0);
    $title              = trim($_POST['title'] ?? '');
    $category           = trim($_POST['category'] ?? 'PARTNERSHIP');
    $badge_label        = trim($_POST['badge_label'] ?? 'NEW');
    $published_date     = trim($_POST['published_date'] ?? date('Y-m-d'));
    $author             = trim($_POST['author'] ?? 'TTBDO Media Communications');
    $summary            = trim($_POST['summary'] ?? '');
    $content            = trim($_POST['content'] ?? '');
    $startups_supported = trim($_POST['startups_supported'] ?? '');
    $meeting_focus      = trim($_POST['meeting_focus'] ?? '');
    $coverage_area      = trim($_POST['coverage_area'] ?? '');
    $is_featured        = isset($_POST['is_featured']) ? 1 : 0;
    $is_published       = isset($_POST['is_published']) ? 1 : 0;

    // Generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    if (empty($slug)) {
        $slug = 'news-' . time();
    }

    if (empty($title) || empty($summary)) {
        $alert_type = 'error';
        $alert_msg = 'Title and Summary are required fields.';
    } else {
        if ($is_featured === 1) {
            // Un-feature existing articles
            db_execute($conn, "UPDATE news SET is_featured = 0");
        }

        if ($id > 0) {
            // Update existing
            $sql = "UPDATE news SET title = ?, category = ?, badge_label = ?, published_date = ?, author = ?, 
                    summary = ?, content = ?, meeting_focus = ?, startups_supported = ?, coverage_area = ?, 
                    is_featured = ?, is_published = ? WHERE id = ?";
            $types = "ssssssssssiii";
            $params = [
                $title, $category, $badge_label, $published_date, $author, 
                $summary, $content, $meeting_focus, $startups_supported, $coverage_area, 
                $is_featured, $is_published, $id
            ];
            $saved = db_execute($conn, $sql, $types, $params);
            if ($saved !== false) {
                $alert_type = 'success';
                $alert_msg = 'Article updated successfully!';
            } else {
                $alert_type = 'error';
                $alert_msg = 'Failed to update article.';
            }
        } else {
            // Insert new
            // Ensure unique slug
            $existing_slug = db_fetch_one($conn, "SELECT id FROM news WHERE slug = ?", "s", [$slug]);
            if ($existing_slug) {
                $slug .= '-' . time();
            }

            $sql = "INSERT INTO news (title, slug, category, badge_label, published_date, author, summary, content, 
                    meeting_focus, startups_supported, coverage_area, is_featured, is_published) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $types = "sssssssssssii";
            $params = [
                $title, $slug, $category, $badge_label, $published_date, $author, $summary, $content,
                $meeting_focus, $startups_supported, $coverage_area, $is_featured, $is_published
            ];
            $saved = db_execute($conn, $sql, $types, $params);
            if ($saved) {
                $alert_type = 'success';
                $alert_msg = 'New article published successfully!';
            } else {
                $alert_type = 'error';
                $alert_msg = 'Failed to publish article.';
            }
        }
    }
}

// If editing, fetch existing record
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_item = db_fetch_one($conn, "SELECT * FROM news WHERE id = ?", "i", [$edit_id]);
}
$is_form_open = isset($_GET['action']) && $_GET['action'] === 'new' || $edit_item !== null;

// Fetch all news items
$all_news = db_fetch_all($conn, "SELECT * FROM news ORDER BY published_date DESC, id DESC");
?>

<!-- Alert Feedback -->
<?php if (!empty($alert_msg)): ?>
<div class="mb-6 p-4 rounded-xl flex items-center gap-3 text-xs <?= $alert_type === 'success' ? 'bg-green-base/10 border border-green-base/20 text-green-dark' : 'bg-maroon-base/10 border border-maroon-base/20 text-maroon-base' ?>">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <?php if ($alert_type === 'success'): ?>
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        <?php else: ?>
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 8.25h.008v.008H12v-.008Z" />
        <?php endif; ?>
    </svg>
    <span><?= e($alert_msg) ?></span>
</div>
<?php endif; ?>

<!-- Header & Add Button -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-serif text-2xl font-bold text-maroon-base">News &amp; Research Highlights</h1>
        <p class="text-xs text-ink-muted mt-0.5">Manage public press releases, research grant calls, and IP achievements.</p>
    </div>
    <div>
        <?php if (!$is_form_open): ?>
        <a href="manage-news.php?action=new"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-xs font-bold transition-all shadow-xs">
            <svg class="w-4 h-4 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Create New Article</span>
        </a>
        <?php else: ?>
        <a href="manage-news.php"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-cream-soft hover:bg-cream-bg border border-border-card text-ink-base text-xs font-semibold transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
            <span>Cancel / Close Form</span>
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Editor Form (Shown when creating or editing) -->
<?php if ($is_form_open): ?>
<div class="bg-white border border-border-card rounded-2xl p-6 sm:p-8 shadow-xs mb-8">
    <h2 class="font-serif text-lg font-bold text-maroon-base mb-4 pb-3 border-b border-border-card">
        <?= $edit_item ? 'Edit Article: ' . e($edit_item['title']) : 'Publish New Article' ?>
    </h2>

    <form action="manage-news.php" method="POST" class="space-y-4">
        <input type="hidden" name="save_news" value="1">
        <input type="hidden" name="id" value="<?= $edit_item['id'] ?? 0 ?>">

        <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
            <div class="md:col-span-8">
                <label for="title" class="block text-sm font-semibold text-ink-base mb-1.5">Article Title *</label>
                <input type="text" id="title" name="title" required
                    value="<?= e($edit_item['title'] ?? '') ?>"
                    placeholder="e.g. UP Cebu and DOST Formalize Innovation Hub"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div class="md:col-span-4">
                <label for="category" class="block text-sm font-semibold text-ink-base mb-1.5">Category *</label>
                <?php 
                $curr_cat = strtoupper(trim($edit_item['category'] ?? 'PARTNERSHIP'));
                $categories_list = [
                    'PARTNERSHIP'          => 'PARTNERSHIP (Strategic Partnerships & MOUs)',
                    'CALL FOR PROPOSALS'   => 'CALL FOR PROPOSALS (Grants & Calls)',
                    'RESEARCH & INNOVATION'=> 'RESEARCH & INNOVATION (Research Highlights)',
                    'PATENT & IP'          => 'PATENT & IP (Inventions & Filings)',
                    'STARTUP & INCUBATION' => 'STARTUP & INCUBATION (TBI / Acceleration)',
                    'CAPACITY BUILDING'    => 'CAPACITY BUILDING (Trainings & Workshops)',
                    'BENCHMARKING & VISITS'=> 'BENCHMARKING & VISITS (Institutional Visits)',
                    'TECHNOLOGY TRANSFER'  => 'TECHNOLOGY TRANSFER (Licensing & Commercialization)',
                    'ANNOUNCEMENT'         => 'ANNOUNCEMENT (General Announcements)',
                    'MILESTONE'            => 'MILESTONE (Institutional Milestones)'
                ];
                ?>
                <select id="category" name="category" required
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base font-sans">
                    <?php foreach ($categories_list as $c_val => $c_label): ?>
                    <option value="<?= $c_val ?>" <?= ($curr_cat === $c_val) ? 'selected' : '' ?>>
                        <?= $c_label ?>
                    </option>
                    <?php endforeach; ?>
                    <?php if (!empty($curr_cat) && !array_key_exists($curr_cat, $categories_list)): ?>
                    <option value="<?= e($curr_cat) ?>" selected><?= e($curr_cat) ?></option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label for="badge_label" class="block text-sm font-semibold text-ink-base mb-1.5">Badge Label</label>
                <input type="text" id="badge_label" name="badge_label"
                    value="<?= e($edit_item['badge_label'] ?? 'FEATURED STORY') ?>"
                    placeholder="FEATURED STORY, NEW, GRANT CALL"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div>
                <label for="published_date" class="block text-sm font-semibold text-ink-base mb-1.5">Published Date *</label>
                <input type="date" id="published_date" name="published_date" required
                    value="<?= e($edit_item['published_date'] ?? date('Y-m-d')) ?>"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div>
                <label for="author" class="block text-sm font-semibold text-ink-base mb-1.5">Author / Credit</label>
                <input type="text" id="author" name="author"
                    value="<?= e($edit_item['author'] ?? 'TTBDO Media Communications') ?>"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
        </div>

        <div>
            <label for="summary" class="block text-sm font-semibold text-ink-base mb-1.5">Summary / Lead Paragraph *</label>
            <textarea id="summary" name="summary" rows="3" required
                placeholder="Short 2-3 sentence overview that appears on news cards..."
                class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base leading-relaxed"><?= e($edit_item['summary'] ?? '') ?></textarea>
        </div>

        <div>
            <label for="content" class="block text-sm font-semibold text-ink-base mb-1.5">Full Article Content (HTML supported)</label>
            <textarea id="content" name="content" rows="6"
                placeholder="<p>Full article text...</p>"
                class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base font-mono leading-relaxed"><?= e($edit_item['content'] ?? '') ?></textarea>
        </div>

        <!-- Metric Callouts (Optional - Startups Assisted, Meeting Focus, Partner/Region) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-cream-soft/60 rounded-xl border border-border-card/60">
            <div>
                <label for="startups_supported" class="block text-xs font-semibold text-ink-base mb-1">Metric 1 (Startups / Teams Assisted)</label>
                <input type="text" id="startups_supported" name="startups_supported"
                    value="<?= e($edit_item['startups_supported'] ?? '') ?>"
                    placeholder="e.g. 12 Startups Assisted"
                    class="w-full px-3 py-2 text-sm rounded border border-border-card bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div>
                <label for="meeting_focus" class="block text-xs font-semibold text-ink-base mb-1">Metric 2 (Meeting / Initiative Focus)</label>
                <input type="text" id="meeting_focus" name="meeting_focus"
                    value="<?= e($edit_item['meeting_focus'] ?? '') ?>"
                    placeholder="e.g. Tech Transfer Benchmarking"
                    class="w-full px-3 py-2 text-sm rounded border border-border-card bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div>
                <label for="coverage_area" class="block text-xs font-semibold text-ink-base mb-1">Metric 3 (Partner / Region Coverage)</label>
                <input type="text" id="coverage_area" name="coverage_area"
                    value="<?= e($edit_item['coverage_area'] ?? '') ?>"
                    placeholder="e.g. BARMM & Central Visayas"
                    class="w-full px-3 py-2 text-sm rounded border border-border-card bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
        </div>

        <!-- Toggles -->
        <div class="flex flex-wrap items-center gap-6 pt-2">
            <label class="inline-flex items-center gap-2.5 cursor-pointer text-sm">
                <input type="checkbox" name="is_featured" value="1" <?= (!empty($edit_item['is_featured']) && $edit_item['is_featured'] == 1) ? 'checked' : '' ?>
                    class="rounded border-border-card text-maroon-base focus:ring-gold-base">
                <span class="font-semibold text-ink-base">Set as Primary Featured Hero Article (displays prominently on left)</span>
            </label>
            <label class="inline-flex items-center gap-2.5 cursor-pointer text-sm">
                <input type="checkbox" name="is_published" value="1" <?= (!isset($edit_item['is_published']) || $edit_item['is_published'] == 1) ? 'checked' : '' ?>
                    class="rounded border-border-card text-green-base focus:ring-green-base">
                <span class="font-semibold text-ink-base">Published (Visible on site)</span>
            </label>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-border-card">
            <a href="manage-news.php" class="px-4 py-2 text-sm font-semibold text-ink-muted hover:text-ink-base">Cancel</a>
            <button type="submit"
                class="px-5 py-2.5 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-sm font-bold shadow-sm transition-all cursor-pointer">
                <?= $edit_item ? 'Save Changes' : 'Publish Article' ?>
            </button>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- News Table List -->
<div class="bg-white border border-border-card rounded-2xl shadow-xs overflow-hidden">
    <div class="p-5 border-b border-border-card flex items-center justify-between">
        <h3 class="font-serif text-lg font-bold text-maroon-base">All Published Articles (<?= count($all_news) ?>)</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-cream-soft border-b border-border-card text-xs uppercase tracking-wider text-ink-muted font-bold">
                <tr>
                    <th class="py-3.5 px-4">Title &amp; Category</th>
                    <th class="py-3.5 px-4">Date</th>
                    <th class="py-3.5 px-4">Author</th>
                    <th class="py-3.5 px-4 text-center">Featured</th>
                    <th class="py-3.5 px-4 text-center">Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-card/60">
                <?php if (!empty($all_news)): foreach ($all_news as $item): ?>
                <tr class="hover:bg-cream-soft/40 transition-colors">
                    <td class="py-3.5 px-4 max-w-sm">
                        <span class="text-xs font-bold text-green-base block uppercase mb-0.5"><?= e($item['category']) ?></span>
                        <strong class="font-semibold text-ink-base block truncate text-sm"><?= e($item['title']) ?></strong>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap text-ink-muted text-sm">
                        <?= date('M d, Y', strtotime($item['published_date'])) ?>
                    </td>
                    <td class="py-3.5 px-4 text-ink-muted truncate max-w-[140px] text-sm">
                        <?= e($item['author']) ?>
                    </td>
                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                        <a href="manage-news.php?toggle_featured=<?= $item['id'] ?>&state=<?= $item['is_featured'] ?>"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-bold transition-all <?= $item['is_featured'] ? 'bg-gold-base text-maroon-dark' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' ?>"
                            title="Click to toggle featured hero status">
                            <svg class="w-3.5 h-3.5 <?= $item['is_featured'] ? 'text-maroon-dark' : 'text-gray-400' ?>" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                            <span><?= $item['is_featured'] ? 'FEATURED' : 'Standard' ?></span>
                        </a>
                    </td>
                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded text-xs font-semibold <?= $item['is_published'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' ?>">
                            <?= $item['is_published'] ? 'Live' : 'Draft' ?>
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-2">
                            <a href="manage-news.php?edit=<?= $item['id'] ?>"
                                class="p-2 rounded-lg hover:bg-cream-soft text-maroon-base hover:text-green-base transition-colors"
                                title="Edit article">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            <a href="manage-news.php?delete=<?= $item['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this article?');"
                                class="p-2 rounded-lg hover:bg-red-50 text-red-700 hover:text-red-900 transition-colors"
                                title="Delete article">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" class="py-8 text-center text-sm text-ink-muted">No news articles found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
