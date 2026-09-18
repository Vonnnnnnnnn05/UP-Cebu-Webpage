<?php
/**
 * Events & Summits Management (CRUD)
 * UP Cebu TTBDO Admin Portal
 */
$page_title = 'Manage Events & Summits';
require_once __DIR__ . '/layout_header.php';

$alert_type = '';
$alert_msg = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $deleted = db_execute($conn, "DELETE FROM events WHERE id = ?", "i", [$delete_id]);
    if ($deleted) {
        $alert_type = 'success';
        $alert_msg = 'Event removed successfully.';
    } else {
        $alert_type = 'error';
        $alert_msg = 'Failed to delete event.';
    }
}

// Handle Featured Toggle
if (isset($_GET['toggle_featured'])) {
    $item_id = (int)$_GET['toggle_featured'];
    $current_state = (int)($_GET['state'] ?? 0);
    $new_state = $current_state === 1 ? 0 : 1;

    if ($new_state === 1) {
        db_execute($conn, "UPDATE events SET is_featured = 0");
    }

    db_execute($conn, "UPDATE events SET is_featured = ? WHERE id = ?", "ii", [$new_state, $item_id]);
    $alert_type = 'success';
    $alert_msg = 'Featured summit status updated.';
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_event'])) {
    $id               = (int)($_POST['id'] ?? 0);
    $title            = trim($_POST['title'] ?? '');
    $category         = trim($_POST['category'] ?? 'WORKSHOP');
    $badge_label      = trim($_POST['badge_label'] ?? 'UPCOMING');
    $event_date       = trim($_POST['event_date'] ?? date('Y-m-d'));
    $start_time       = trim($_POST['start_time'] ?? '09:00 AM');
    $end_time         = trim($_POST['end_time'] ?? '05:00 PM');
    $venue            = trim($_POST['venue'] ?? 'UP Cebu Lahug');
    $venue_type       = trim($_POST['venue_type'] ?? 'in-person');
    $summary          = trim($_POST['summary'] ?? '');
    $description      = trim($_POST['description'] ?? '');
    $registration_url = trim($_POST['registration_url'] ?? '#contact');
    $status           = trim($_POST['status'] ?? 'upcoming');
    $is_featured      = isset($_POST['is_featured']) ? 1 : 0;

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    if (empty($slug)) {
        $slug = 'event-' . time();
    }

    if (empty($title) || empty($event_date) || empty($summary)) {
        $alert_type = 'error';
        $alert_msg = 'Title, Event Date, and Summary are required fields.';
    } else {
        if ($is_featured === 1) {
            db_execute($conn, "UPDATE events SET is_featured = 0");
        }

        if ($id > 0) {
            // Update
            $sql = "UPDATE events SET title = ?, category = ?, badge_label = ?, event_date = ?, 
                    start_time = ?, end_time = ?, venue = ?, venue_type = ?, summary = ?, 
                    description = ?, registration_url = ?, status = ?, is_featured = ? WHERE id = ?";
            $types = "ssssssssssssii";
            $params = [
                $title, $category, $badge_label, $event_date,
                $start_time, $end_time, $venue, $venue_type, $summary,
                $description, $registration_url, $status, $is_featured, $id
            ];
            $saved = db_execute($conn, $sql, $types, $params);
            if ($saved !== false) {
                $alert_type = 'success';
                $alert_msg = 'Event updated successfully!';
            } else {
                $alert_type = 'error';
                $alert_msg = 'Failed to update event.';
            }
        } else {
            // Insert
            $existing_slug = db_fetch_one($conn, "SELECT id FROM events WHERE slug = ?", "s", [$slug]);
            if ($existing_slug) {
                $slug .= '-' . time();
            }

            $sql = "INSERT INTO events (title, slug, category, badge_label, event_date, start_time, end_time, 
                    venue, venue_type, summary, description, registration_url, status, is_featured) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $types = "sssssssssssssi";
            $params = [
                $title, $slug, $category, $badge_label, $event_date, $start_time, $end_time,
                $venue, $venue_type, $summary, $description, $registration_url, $status, $is_featured
            ];
            $saved = db_execute($conn, $sql, $types, $params);
            if ($saved) {
                $alert_type = 'success';
                $alert_msg = 'New event scheduled successfully!';
            } else {
                $alert_type = 'error';
                $alert_msg = 'Failed to create event.';
            }
        }
    }
}

// Check edit
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_item = db_fetch_one($conn, "SELECT * FROM events WHERE id = ?", "i", [$edit_id]);
}
$is_form_open = isset($_GET['action']) && $_GET['action'] === 'new' || $edit_item !== null;

// Fetch all events
$all_events = db_fetch_all($conn, "SELECT * FROM events ORDER BY event_date ASC");
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
        <h1 class="font-serif text-2xl font-bold text-maroon-base">Calendar &amp; Engagements</h1>
        <p class="text-xs text-ink-muted mt-0.5">Schedule innovation summits, IP legal clinics, pitch demo days, and workshops.</p>
    </div>
    <div>
        <?php if (!$is_form_open): ?>
        <a href="manage-events.php?action=new"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-green-base hover:bg-green-hover text-white text-xs font-bold transition-all shadow-xs">
            <svg class="w-4 h-4 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Schedule New Event</span>
        </a>
        <?php else: ?>
        <a href="manage-events.php"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-cream-soft hover:bg-cream-bg border border-border-card text-ink-base text-xs font-semibold transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
            <span>Cancel / Close Form</span>
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Editor Form -->
<?php if ($is_form_open): ?>
<div class="bg-white border border-border-card rounded-2xl p-6 sm:p-8 shadow-xs mb-8">
    <h2 class="font-serif text-lg font-bold text-maroon-base mb-4 pb-3 border-b border-border-card">
        <?= $edit_item ? 'Edit Event: ' . e($edit_item['title']) : 'Schedule New Innovation Event' ?>
    </h2>

    <form action="manage-events.php" method="POST" class="space-y-4">
        <input type="hidden" name="save_event" value="1">
        <input type="hidden" name="id" value="<?= $edit_item['id'] ?? 0 ?>">

        <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
            <div class="md:col-span-8">
                <label for="title" class="block text-sm font-semibold text-ink-base mb-1.5">Event Title *</label>
                <input type="text" id="title" name="title" required
                    value="<?= e($edit_item['title'] ?? '') ?>"
                    placeholder="e.g. Central Visayas Innovation Summit & Startup Demo Day"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div class="md:col-span-4">
                <label for="category" class="block text-sm font-semibold text-ink-base mb-1.5">Category / Type *</label>
                <input type="text" id="category" name="category" required
                    value="<?= e($edit_item['category'] ?? 'ANNUAL SUMMIT') ?>"
                    placeholder="ANNUAL SUMMIT, IP LEGAL CLINIC, WORKSHOP"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
            <div>
                <label for="event_date" class="block text-sm font-semibold text-ink-base mb-1.5">Event Date *</label>
                <input type="date" id="event_date" name="event_date" required
                    value="<?= e($edit_item['event_date'] ?? date('Y-m-d', strtotime('+7 days'))) ?>"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div>
                <label for="start_time" class="block text-sm font-semibold text-ink-base mb-1.5">Start Time</label>
                <input type="text" id="start_time" name="start_time"
                    value="<?= e($edit_item['start_time'] ?? '09:00 AM') ?>"
                    placeholder="09:00 AM"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div>
                <label for="end_time" class="block text-sm font-semibold text-ink-base mb-1.5">End Time</label>
                <input type="text" id="end_time" name="end_time"
                    value="<?= e($edit_item['end_time'] ?? '04:30 PM') ?>"
                    placeholder="04:30 PM"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div>
                <label for="venue_type" class="block text-sm font-semibold text-ink-base mb-1.5">Venue Format</label>
                <select id="venue_type" name="venue_type"
                    class="w-full px-3 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
                    <option value="in-person" <?= ($edit_item['venue_type'] ?? '') === 'in-person' ? 'selected' : '' ?>>In-Person Only</option>
                    <option value="hybrid" <?= ($edit_item['venue_type'] ?? 'hybrid') === 'hybrid' ? 'selected' : '' ?>>Hybrid (Campus + Live Stream)</option>
                    <option value="virtual" <?= ($edit_item['venue_type'] ?? '') === 'virtual' ? 'selected' : '' ?>>Virtual (Zoom / Webinar)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
            <div class="sm:col-span-8">
                <label for="venue" class="block text-sm font-semibold text-ink-base mb-1.5">Physical Location / Room</label>
                <input type="text" id="venue" name="venue"
                    value="<?= e($edit_item['venue'] ?? 'UP Cebu SRP Campus • Performing Arts Hall') ?>"
                    placeholder="e.g. 3rd Floor TIC, UP Cebu Lahug"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div class="sm:col-span-4">
                <label for="status" class="block text-sm font-semibold text-ink-base mb-1.5">Status</label>
                <select id="status" name="status"
                    class="w-full px-3 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
                    <option value="upcoming" <?= ($edit_item['status'] ?? 'upcoming') === 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                    <option value="completed" <?= ($edit_item['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= ($edit_item['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
        </div>

        <div>
            <label for="summary" class="block text-sm font-semibold text-ink-base mb-1.5">Event Summary / Overview *</label>
            <textarea id="summary" name="summary" rows="3" required
                placeholder="Overview of the event, keynote topics, or who should attend..."
                class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base leading-relaxed"><?= e($edit_item['summary'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="registration_url" class="block text-sm font-semibold text-ink-base mb-1.5">Registration Link or Target</label>
                <input type="text" id="registration_url" name="registration_url"
                    value="<?= e($edit_item['registration_url'] ?? '#contact') ?>"
                    placeholder="#contact or https://forms.gle/..."
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
            <div>
                <label for="badge_label" class="block text-sm font-semibold text-ink-base mb-1.5">Hero Badge Label</label>
                <input type="text" id="badge_label" name="badge_label"
                    value="<?= e($edit_item['badge_label'] ?? 'REGISTRATION OPEN') ?>"
                    placeholder="REGISTRATION OPEN, FLAGSHIP EVENT"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>
        </div>

        <div class="pt-2">
            <label class="inline-flex items-center gap-2.5 cursor-pointer text-sm">
                <input type="checkbox" name="is_featured" value="1" <?= (!empty($edit_item['is_featured']) && $edit_item['is_featured'] == 1) ? 'checked' : '' ?>
                    class="rounded border-border-card text-gold-base focus:ring-gold-base">
                <span class="font-semibold text-ink-base">Set as Featured Flagship Summit (renders as the large full-width banner card)</span>
            </label>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-border-card">
            <a href="manage-events.php" class="px-4 py-2 text-sm font-semibold text-ink-muted hover:text-ink-base">Cancel</a>
            <button type="submit"
                class="px-5 py-2.5 rounded-lg bg-green-base hover:bg-green-hover text-white text-sm font-bold shadow-sm transition-all cursor-pointer">
                <?= $edit_item ? 'Save Changes' : 'Schedule Event' ?>
            </button>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Events List Table -->
<div class="bg-white border border-border-card rounded-2xl shadow-xs overflow-hidden">
    <div class="p-5 border-b border-border-card flex items-center justify-between">
        <h3 class="font-serif text-lg font-bold text-maroon-base">Scheduled Events (<?= count($all_events) ?>)</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-cream-soft border-b border-border-card text-xs uppercase tracking-wider text-ink-muted font-bold">
                <tr>
                    <th class="py-3.5 px-4">Event &amp; Category</th>
                    <th class="py-3.5 px-4">Date &amp; Time</th>
                    <th class="py-3.5 px-4">Venue</th>
                    <th class="py-3.5 px-4 text-center">Format</th>
                    <th class="py-3.5 px-4 text-center">Flagship</th>
                    <th class="py-3.5 px-4 text-center">Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-card/60">
                <?php if (!empty($all_events)): foreach ($all_events as $item): ?>
                <tr class="hover:bg-cream-soft/40 transition-colors">
                    <td class="py-3.5 px-4 max-w-sm">
                        <span class="text-xs font-bold text-maroon-base block uppercase mb-0.5"><?= e($item['category']) ?></span>
                        <strong class="font-semibold text-ink-base block truncate text-sm"><?= e($item['title']) ?></strong>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap text-sm">
                        <strong class="text-ink-base block font-semibold"><?= date('M d, Y', strtotime($item['event_date'])) ?></strong>
                        <span class="text-xs text-ink-muted block"><?= e($item['start_time']) ?></span>
                    </td>
                    <td class="py-3.5 px-4 text-ink-muted truncate max-w-[150px] text-sm">
                        <?= e($item['venue']) ?>
                    </td>
                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded text-xs font-semibold uppercase 
                            <?= $item['venue_type'] === 'hybrid' ? 'bg-gold-light text-gold-deep' : ($item['venue_type'] === 'virtual' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') ?>">
                            <?= e($item['venue_type']) ?>
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                        <a href="manage-events.php?toggle_featured=<?= $item['id'] ?>&state=<?= $item['is_featured'] ?>"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-bold transition-all <?= $item['is_featured'] ? 'bg-gold-base text-maroon-dark' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' ?>"
                            title="Click to toggle flagship summit card">
                            <svg class="w-3.5 h-3.5 <?= $item['is_featured'] ? 'text-maroon-dark' : 'text-gray-400' ?>" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                            <span><?= $item['is_featured'] ? 'FLAGSHIP' : 'Standard' ?></span>
                        </a>
                    </td>
                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded text-xs font-semibold 
                            <?= $item['status'] === 'upcoming' ? 'bg-green-100 text-green-800' : ($item['status'] === 'completed' ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-800') ?>">
                            <?= ucfirst(e($item['status'])) ?>
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-2">
                            <a href="manage-events.php?edit=<?= $item['id'] ?>"
                                class="p-2 rounded-lg hover:bg-cream-soft text-maroon-base hover:text-green-base transition-colors"
                                title="Edit event">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            <a href="manage-events.php?delete=<?= $item['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this event?');"
                                class="p-2 rounded-lg hover:bg-red-50 text-red-700 hover:text-red-900 transition-colors"
                                title="Delete event">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="7" class="py-8 text-center text-sm text-ink-muted">No events scheduled.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>

