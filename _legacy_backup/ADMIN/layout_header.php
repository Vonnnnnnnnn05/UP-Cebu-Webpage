<?php
/**
 * Shared Admin Header & Navigation Bar
 * UP Cebu TTBDO
 */
require_once __DIR__ . '/auth_check.php';

$current_page = basename($_SERVER['PHP_SELF']);

// Count pending inquiries for navbar badge
$pending_inquiries_count = db_fetch_one($conn, "SELECT COUNT(*) as total FROM inquiries WHERE status = 'pending'")['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Portal' ?> | UP Cebu TTBDO</title>

    <!-- Google Fonts: UP Authorized Typefaces (Arsenal, Source Serif 4, Libre Baskerville, Source Sans 3, Style Script, MonteCarlo) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arsenal:ital,wght@0,400;0,700;1,400;1,700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=MonteCarlo&family=Source+Sans+3:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;0,8..60,700;1,8..60,400;1,8..60,700&family=Style+Script&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 Play CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <!-- SweetAlert2 & Animate.css -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* Sleek Balanced SweetAlert2 */
        div.swal2-container {
            padding: 1rem !important;
        }
        div.swal2-popup {
            width: 24rem !important;
            max-width: calc(100vw - 2rem) !important;
            padding: 1.4rem 1.6rem !important;
            border-radius: 1.25rem !important;
            font-size: 0.95rem !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.18), 0 10px 10px -5px rgba(0, 0, 0, 0.08) !important;
        }
        div.swal2-popup.swal2-toast {
            width: auto !important;
            max-width: 23rem !important;
            padding: 0.75rem 1.1rem !important;
            border-radius: 0.85rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.12) !important;
        }
        .swal2-toast .swal2-title {
            font-size: 0.92rem !important;
            font-weight: 700 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .swal2-toast .swal2-html-container {
            font-size: 0.84rem !important;
            margin: 0.2rem 0 0 0 !important;
            color: #5A554D !important;
        }
        .swal2-toast .swal2-icon {
            width: 1.6rem !important;
            height: 1.6rem !important;
            margin: 0 0.6rem 0 0 !important;
            min-width: 1.6rem !important;
        }
        .swal2-icon {
            width: 3.4rem !important;
            height: 3.4rem !important;
            margin: 0.35rem auto 0.9rem auto !important;
            transform: scale(0.92);
        }
        .swal2-icon .swal2-icon-content {
            font-size: 1.8rem !important;
        }
        .swal2-title {
            font-size: 1.2rem !important;
            font-weight: 700 !important;
            color: #26231F !important;
            margin: 0 0 0.45rem 0 !important;
            padding: 0 !important;
            line-height: 1.35 !important;
        }
        .swal2-html-container {
            font-size: 0.92rem !important;
            color: #5A554D !important;
            margin: 0 0 1rem 0 !important;
            line-height: 1.5 !important;
        }
        .swal2-actions {
            margin-top: 0.65rem !important;
            gap: 0.5rem !important;
        }
        .swal2-actions button {
            font-size: 0.88rem !important;
            font-weight: 600 !important;
            padding: 0.55rem 1.2rem !important;
            border-radius: 0.55rem !important;
            min-height: auto !important;
        }
        .swal2-timer-progress-bar {
            height: 3.5px !important;
            background: #D4A017 !important;
        }
    </style>
    <style type="text/tailwindcss">
        @theme {
            --color-maroon-dark: #4D0A0C;
            --color-maroon-base: #7B1113;
            --color-maroon-hover: #911618;
            --color-green-base: #014421;
            --color-green-dark: #002B15;
            --color-gold-base: #D4A017;
            --color-gold-light: #F4E7C0;
            --color-cream-bg: #F3EBDD;
            --color-cream-soft: #FBF8F2;
            --color-ink-base: #26231F;
            --color-ink-muted: #5A554D;
            /* UP Authorized Typography System */
            --font-heading: "Arsenal", "Libertinus Sans", sans-serif;
            --font-title: "Arsenal", "Libertinus Sans", sans-serif;
            --font-serif: "Arsenal", "Libertinus Sans", sans-serif;
            --font-sans: "Source Sans 3", "Open Sans", Roboto, sans-serif;
            --font-official-serif: "Source Serif 4", "Libre Baskerville", Georgia, serif;
            --font-script: "Style Script", "MonteCarlo", cursive;
            --font-logotype: "Padayon", "Arsenal", sans-serif;
        }
        body {
            font-family: var(--font-sans);
            font-size: 0.95rem;
            line-height: 1.55;
        }
        h1, h2, h3, h4, h5, h6, .font-heading, .font-title, .font-serif {
            font-family: var(--font-heading);
        }
        .swal2-title {
            font-family: var(--font-heading) !important;
        }
        .swal2-html-container {
            font-family: var(--font-sans) !important;
        }
    </style>
</head>
<body class="bg-cream-bg text-ink-base min-h-screen flex flex-col antialiased">

    <!-- Top Navigation Header -->
    <header class="bg-maroon-dark text-white border-b-2 border-gold-base sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Mark -->
                <div class="flex items-center gap-3">
                    <a href="index.php" class="flex items-center gap-2.5">
                        <div class="bg-white rounded p-1 flex items-center justify-center">
                            <img src="../assets/ttbdo-full-logo.png" alt="TTBDO Logo" class="h-7 sm:h-8 w-auto object-contain" />
                        </div>
                        <div class="hidden sm:block">
                            <span class="block text-[10px] uppercase tracking-wider text-gold-light font-bold">ADMINISTRATION PORTAL</span>
                            <span class="block font-serif text-base font-bold leading-tight">UP Cebu TTBDO</span>
                        </div>
                    </a>
                </div>

                <!-- Admin Profile & Quick Links -->
                <div class="flex items-center gap-3">
                    <a href="../index.php" target="_blank"
                        class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition-colors">
                        <span>View Website</span>
                        <svg class="w-4 h-4 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>

                    <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-maroon-base/80 border border-gold-base/30 text-sm">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                        <span class="font-bold text-white"><?= e($current_admin['full_name']) ?></span>
                        <span class="text-xs text-gold-light uppercase tracking-wider hidden sm:inline">(<?= e($current_admin['role']) ?>)</span>
                    </div>

                    <button type="button" onclick="confirmAdminLogout()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-red-900/60 hover:bg-red-800 text-white text-sm font-bold transition-colors cursor-pointer"
                        title="Sign Out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </div>
            </div>

            <!-- Sub Navigation Bar -->
            <nav class="flex items-center gap-1 sm:gap-2 overflow-x-auto py-2 border-t border-white/10 text-sm font-medium scrollbar-none">
                <a href="index.php"
                    class="px-3.5 py-2 rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap <?= $current_page === 'index.php' ? 'bg-gold-base text-maroon-dark font-bold' : 'text-cream-bg/85 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="manage-news.php"
                    class="px-3.5 py-2 rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap <?= $current_page === 'manage-news.php' ? 'bg-gold-base text-maroon-dark font-bold' : 'text-cream-bg/85 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                    </svg>
                    <span>News &amp; Research</span>
                </a>

                <a href="manage-events.php"
                    class="px-3.5 py-2 rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap <?= $current_page === 'manage-events.php' ? 'bg-gold-base text-maroon-dark font-bold' : 'text-cream-bg/85 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>Events &amp; Summits</span>
                </a>

                <a href="manage-inquiries.php"
                    class="px-3.5 py-2 rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap <?= $current_page === 'manage-inquiries.php' ? 'bg-gold-base text-maroon-dark font-bold' : 'text-cream-bg/85 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <span>Inquiries</span>
                    <?php if ($pending_inquiries_count > 0): ?>
                    <span class="px-2 py-0.5 rounded-full bg-gold-base text-maroon-dark text-xs font-bold">
                        <?= $pending_inquiries_count ?>
                    </span>
                    <?php endif; ?>
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
