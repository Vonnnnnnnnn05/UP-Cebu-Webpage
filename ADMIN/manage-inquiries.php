<?php
/**
 * Inquiries & Consultations Manager
 * UP Cebu TTBDO Admin Portal
 */
$page_title = 'Inquiries & Consultations';
require_once __DIR__ . '/layout_header.php';

$alert_type = '';
$alert_msg = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $deleted = db_execute($conn, "DELETE FROM inquiries WHERE id = ?", "i", [$delete_id]);
    if ($deleted) {
        $alert_type = 'success';
        $alert_msg = 'Inquiry record deleted.';
    } else {
        $alert_type = 'error';
        $alert_msg = 'Failed to delete inquiry.';
    }
}

// Handle Status & Notes Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_inquiry'])) {
    $inq_id     = (int)($_POST['id'] ?? 0);
    $new_status = trim($_POST['status'] ?? 'pending');
    $notes      = trim($_POST['admin_notes'] ?? '');

    if ($inq_id > 0) {
        $updated = db_execute($conn, "UPDATE inquiries SET status = ?, admin_notes = ? WHERE id = ?", "ssi", [$new_status, $notes, $inq_id]);
        if ($updated !== false) {
            $alert_type = 'success';
            $alert_msg = 'Inquiry status updated successfully.';
        } else {
            $alert_type = 'error';
            $alert_msg = 'Failed to update status.';
        }
    }
}

// Current Filter
$filter_status = $_GET['status'] ?? 'all';
$sql = "SELECT * FROM inquiries";
$types = "";
$params = [];

if (in_array($filter_status, ['pending', 'in_review', 'resolved', 'archived'])) {
    $sql .= " WHERE status = ?";
    $types = "s";
    $params = [$filter_status];
}
$sql .= " ORDER BY created_at DESC";

$inquiries_list = db_fetch_all($conn, $sql, $types, $params);

// Selected Inquiry for Modal / Detail View
$selected_inquiry = null;
if (isset($_GET['id'])) {
    $view_id = (int)$_GET['id'];
    $selected_inquiry = db_fetch_one($conn, "SELECT * FROM inquiries WHERE id = ?", "i", [$view_id]);
}
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

<!-- Header & Filter Tabs -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base">Inquiries &amp; Consultations</h1>
        <p class="text-sm text-ink-muted mt-1">Review, respond to, and track requests from innovators, researchers, and partners.</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-1.5 bg-white border border-border-card p-1 rounded-xl shadow-xs text-sm overflow-x-auto">
        <a href="manage-inquiries.php?status=all"
            class="px-3.5 py-1.5 rounded-lg transition-colors <?= $filter_status === 'all' ? 'bg-maroon-base text-white font-bold' : 'text-ink-muted hover:text-ink-base' ?>">
            All
        </a>
        <a href="manage-inquiries.php?status=pending"
            class="px-3.5 py-1.5 rounded-lg transition-colors <?= $filter_status === 'pending' ? 'bg-amber-500 text-white font-bold' : 'text-ink-muted hover:text-ink-base' ?>">
            Pending
        </a>
        <a href="manage-inquiries.php?status=in_review"
            class="px-3.5 py-1.5 rounded-lg transition-colors <?= $filter_status === 'in_review' ? 'bg-blue-600 text-white font-bold' : 'text-ink-muted hover:text-ink-base' ?>">
            In Review
        </a>
        <a href="manage-inquiries.php?status=resolved"
            class="px-3.5 py-1.5 rounded-lg transition-colors <?= $filter_status === 'resolved' ? 'bg-green-base text-white font-bold' : 'text-ink-muted hover:text-ink-base' ?>">
            Resolved
        </a>
        <a href="manage-inquiries.php?status=archived"
            class="px-3.5 py-1.5 rounded-lg transition-colors <?= $filter_status === 'archived' ? 'bg-gray-600 text-white font-bold' : 'text-ink-muted hover:text-ink-base' ?>">
            Archived
        </a>
    </div>
</div>

<!-- If Selected Inquiry (Detail View Card) -->
<?php if ($selected_inquiry): ?>
<div class="bg-white border-2 border-maroon-base/40 rounded-2xl p-6 sm:p-8 shadow-md mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-border-card">
        <div>
            <div class="flex items-center gap-2.5 mb-1.5">
                <span class="text-xs px-3 py-0.5 rounded-full font-bold uppercase
                    <?= $selected_inquiry['status'] === 'pending' ? 'bg-amber-100 text-amber-800' : ($selected_inquiry['status'] === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') ?>">
                    <?= strtoupper(str_replace('_', ' ', e($selected_inquiry['status']))) ?>
                </span>
                <span class="text-sm text-ink-muted">Received <?= date('F j, Y - g:i A', strtotime($selected_inquiry['created_at'])) ?></span>
            </div>
            <h2 class="font-serif text-xl sm:text-2xl font-bold text-maroon-base"><?= e($selected_inquiry['subject']) ?></h2>
        </div>
        <a href="manage-inquiries.php<?= $filter_status !== 'all' ? '?status=' . e($filter_status) : '' ?>"
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-border-card text-sm font-semibold text-ink-muted hover:text-ink-base transition-colors self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
            <span>Close Details</span>
        </a>
    </div>

    <!-- Submitter Details Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-5 bg-cream-soft rounded-xl border border-border-card/60 mb-6 text-sm">
        <div>
            <span class="block text-xs text-ink-muted uppercase font-semibold mb-0.5">Submitter Name</span>
            <strong class="text-ink-base font-bold text-sm"><?= e($selected_inquiry['full_name']) ?></strong>
        </div>
        <div>
            <span class="block text-xs text-ink-muted uppercase font-semibold mb-0.5">Email</span>
            <a href="mailto:<?= e($selected_inquiry['email']) ?>?subject=Re: <?= urlencode($selected_inquiry['subject']) ?>" class="text-maroon-base hover:text-green-base font-semibold text-sm">
                <?= e($selected_inquiry['email']) ?> &rarr;
            </a>
        </div>
        <div>
            <span class="block text-xs text-ink-muted uppercase font-semibold mb-0.5">Affiliation</span>
            <span class="text-ink-base font-medium capitalize text-sm"><?= e(str_replace('_', ' ', $selected_inquiry['affiliation'])) ?></span>
        </div>
        <div>
            <span class="block text-xs text-ink-muted uppercase font-semibold mb-0.5">Service Type</span>
            <span class="text-ink-base font-medium capitalize text-sm"><?= e(str_replace('_', ' ', $selected_inquiry['inquiry_type'])) ?></span>
        </div>
    </div>

    <!-- Message Content -->
    <div class="mb-6">
        <label class="block text-xs font-bold text-ink-muted mb-2 uppercase tracking-wider">Inquiry Message</label>
        <div class="p-5 bg-white rounded-xl border border-border-card text-sm sm:text-base leading-relaxed text-ink-base whitespace-pre-wrap font-sans">
            <?= e($selected_inquiry['message']) ?>
        </div>
    </div>

    <!-- Admin Status Update & Notes Form -->
    <form action="manage-inquiries.php<?= $filter_status !== 'all' ? '?status=' . e($filter_status) : '' ?>" method="POST" class="pt-4 border-t border-border-card">
        <input type="hidden" name="update_inquiry" value="1">
        <input type="hidden" name="id" value="<?= $selected_inquiry['id'] ?>">

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-4">
                <label for="status" class="block text-sm font-semibold text-ink-base mb-1.5">Update Status</label>
                <select id="status" name="status"
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
                    <option value="pending" <?= $selected_inquiry['status'] === 'pending' ? 'selected' : '' ?>>Pending Review</option>
                    <option value="in_review" <?= $selected_inquiry['status'] === 'in_review' ? 'selected' : '' ?>>In Review / Assigned</option>
                    <option value="resolved" <?= $selected_inquiry['status'] === 'resolved' ? 'selected' : '' ?>>Resolved / Handled</option>
                    <option value="archived" <?= $selected_inquiry['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>

            <div class="sm:col-span-6">
                <label for="admin_notes" class="block text-sm font-semibold text-ink-base mb-1.5">Internal Staff Notes</label>
                <input type="text" id="admin_notes" name="admin_notes"
                    value="<?= e($selected_inquiry['admin_notes'] ?? '') ?>"
                    placeholder="e.g. Endorsed to Prof. Santos for prior art assessment..."
                    class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base">
            </div>

            <div class="sm:col-span-2">
                <button type="submit"
                    class="w-full py-2.5 px-4 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-sm font-bold shadow-xs transition-colors cursor-pointer">
                    Save Status
                </button>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Inquiries Table -->
<div class="bg-white border border-border-card rounded-2xl shadow-xs overflow-hidden">
    <div class="p-5 border-b border-border-card flex items-center justify-between">
        <h3 class="font-serif text-lg font-bold text-maroon-base">
            <?= ucfirst(e($filter_status)) ?> Inquiries (<?= count($inquiries_list) ?>)
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-cream-soft border-b border-border-card text-xs uppercase tracking-wider text-ink-muted font-bold">
                <tr>
                    <th class="py-3.5 px-4">Submitter</th>
                    <th class="py-3.5 px-4">Subject &amp; Message</th>
                    <th class="py-3.5 px-4">Affiliation</th>
                    <th class="py-3.5 px-4">Date</th>
                    <th class="py-3.5 px-4 text-center">Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-card/60">
                <?php if (!empty($inquiries_list)): foreach ($inquiries_list as $item): ?>
                <tr class="hover:bg-cream-soft/40 transition-colors <?= (isset($selected_inquiry['id']) && $selected_inquiry['id'] == $item['id']) ? 'bg-gold-light/20' : '' ?>">
                    <td class="py-3.5 px-4 whitespace-nowrap">
                        <strong class="text-ink-base block text-sm font-bold"><?= e($item['full_name']) ?></strong>
                        <span class="text-xs text-ink-muted block mt-0.5"><?= e($item['email']) ?></span>
                    </td>
                    <td class="py-3.5 px-4 max-w-sm">
                        <strong class="text-maroon-base block truncate text-sm font-semibold"><?= e($item['subject']) ?></strong>
                        <p class="text-xs text-ink-muted line-clamp-1 mt-0.5"><?= e($item['message']) ?></p>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap capitalize text-ink-muted text-sm">
                        <?= e(str_replace('_', ' ', $item['affiliation'])) ?>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap text-ink-muted text-sm">
                        <?= date('M d, Y', strtotime($item['created_at'])) ?>
                    </td>
                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            <?= $item['status'] === 'pending' ? 'bg-amber-100 text-amber-800' : ($item['status'] === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') ?>">
                            <?= strtoupper(str_replace('_', ' ', e($item['status']))) ?>
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-2">
                            <a href="manage-inquiries.php?id=<?= $item['id'] ?><?= $filter_status !== 'all' ? '&status=' . e($filter_status) : '' ?>"
                                class="px-3 py-1.5 rounded-lg bg-cream-soft hover:bg-cream-bg border border-border-card text-ink-base hover:text-maroon-base font-bold text-xs transition-colors"
                                title="View details">
                                View
                            </a>
                            <a href="manage-inquiries.php?delete=<?= $item['id'] ?><?= $filter_status !== 'all' ? '&status=' . e($filter_status) : '' ?>"
                                onclick="return confirm('Are you sure you want to delete this inquiry record?');"
                                class="p-1.5 rounded-lg hover:bg-red-50 text-red-700 hover:text-red-900 transition-colors"
                                title="Delete inquiry">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" class="py-8 text-center text-sm text-ink-muted">No inquiries found in this view.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
