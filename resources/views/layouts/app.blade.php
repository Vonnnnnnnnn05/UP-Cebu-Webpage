<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Technology Transfer and Business Development Office - University of the Philippines Cebu">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UP Cebu TTBDO')</title>

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
            /* Dominant Neutral Background */
            --color-cream-bg: #F3EBDD;
            --color-cream-soft: #FBF8F2;

            /* Primary Brand (UP Maroon - PANTONE 1955C #7B1113) */
            --color-maroon-dark: #4D0A0C;
            --color-maroon-base: #7B1113;
            --color-maroon-hover: #911618;
            --color-maroon-light: #A81B1F;
            --color-red-base: #7B1113;
            --color-red-card: #7B1113;

            /* Secondary Brand (Forest Green - PANTONE 7484C #014421) */
            --color-green-dark: #002B15;
            --color-green-base: #014421;
            --color-green-hover: #0D5D30;
            --color-green-light: #1B733F;

            /* Accent Brand (University Gold - PANTONE 1235C #D4A017 / #F2A900) */
            --color-gold-base: #D4A017;
            --color-gold-hover: #BF8E0F;
            --color-gold-deep: #8E5E0B;
            --color-gold-light: #F4E7C0;

            /* Neutrals & Text */
            --color-ink-base: #26231F;
            --color-ink-muted: #5A554D;
            --color-ink-subtle: #4B4640;
            --color-border-card: #DDD2C2;

            /* UP Authorized Typeface System */
            --font-heading: "Arsenal", "Libertinus Sans", sans-serif;
            --font-title: "Arsenal", "Libertinus Sans", sans-serif;
            --font-serif: "Arsenal", "Libertinus Sans", sans-serif;
            --font-sans: "Source Sans 3", "Open Sans", Roboto, sans-serif;
            --font-official-serif: "Source Serif 4", "Libre Baskerville", Georgia, serif;
            --font-script: "Style Script", "MonteCarlo", cursive;
            --font-logotype: "Padayon", "Arsenal", sans-serif;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 5rem;
            overflow-x: clip;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--color-cream-bg);
            color: var(--color-ink-base);
            overflow-x: clip;
        }

        /* UP Authorized Typography Hierarchy */
        h1, h2, h3, h4, h5, h6, .font-heading, .font-title, .font-serif {
            font-family: var(--font-heading);
        }

        .font-official-body, .official-body, .font-source-serif, .font-libre-baskerville {
            font-family: var(--font-official-serif) !important;
        }

        .font-script, .font-style-script, .font-montecarlo {
            font-family: var(--font-script) !important;
        }

        .font-logotype, .official-logotype {
            font-family: var(--font-logotype) !important;
        }

        .swal2-title {
            font-family: var(--font-heading) !important;
        }

        .swal2-html-container {
            font-family: var(--font-sans) !important;
        }

        /* Ensure crisp white text on buttons and dark elements */
        .text-white, a.text-white, a.text-white span, button.text-white {
            color: #ffffff !important;
        }

        .brand-mark-ring::after {
            content: "";
            position: absolute;
            inset: 5px;
            border: 1px solid rgba(201, 154, 36, 0.6);
            border-radius: 9999px;
        }

        /* Desktop Navigation Links */
        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            position: relative;
            display: flex;
            align-items: center;
            height: 100%;
            padding-left: 0.25rem;
            padding-right: 0.25rem;
            font-weight: 600;
            white-space: nowrap;
            flex-shrink: 0;
            transition: color 0.25s ease;
        }

        .nav-link:hover {
            color: #C99A24;
        }

        .nav-link.active {
            color: #C99A24 !important;
            font-weight: 600;
            white-space: nowrap;
        }

        .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2.5px;
            background-color: #C99A24;
        }

        /* Mobile Dropdown Navigation Links */
        .mobile-nav-link {
            color: rgba(255, 255, 255, 0.9);
            background-color: transparent;
            border-left: 3px solid transparent;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.25s ease;
        }

        .mobile-nav-link:hover {
            color: #C99A24;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .mobile-nav-link.active {
            color: #C99A24 !important;
            background-color: rgba(201, 154, 36, 0.12);
            border-left: 3px solid #C99A24;
            font-weight: 700;
        }

        .mobile-nav-link svg {
            color: rgba(255, 255, 255, 0.5);
            transition: color 0.25s ease;
        }

        .mobile-nav-link:hover svg,
        .mobile-nav-link.active svg {
            color: #C99A24;
        }

        /* Dropdown Box & Item Hover Enhancements */
        .group\/dropdown:hover > .nav-link {
            color: #C99A24 !important;
        }

        .group\/dropdown:hover > .nav-link svg {
            color: #C99A24 !important;
        }

        .dropdown-panel-box {
            transform: translateY(6px);
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1),
                        visibility 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .group\/dropdown:hover .dropdown-panel-box {
            transform: translateY(0);
        }

        .dropdown-link-item {
            position: relative;
            border-left: 2.5px solid transparent;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .dropdown-link-item:hover {
            background-color: rgba(255, 255, 255, 0.12);
            border-left-color: #C99A24;
            padding-left: 0.85rem !important;
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .dropdown-link-item:hover .dropdown-item-title {
            color: #C99A24;
        }

        .dropdown-link-item:hover .dropdown-item-desc {
            color: rgba(255, 255, 255, 0.95);
        }

        .dropdown-link-item:hover .dropdown-item-arrow {
            opacity: 1 !important;
            transform: translateX(0) !important;
            color: #C99A24;
        }

        /* Reading Scroll Progress Bar */
        #scroll-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3.5px;
            background: linear-gradient(90deg, #C99A24, #F4E7C0, #C99A24);
            background-size: 200% 100%;
            z-index: 9999;
            transition: width 0.25s cubic-bezier(0.2, 0.8, 0.4, 1);
            pointer-events: none;
            box-shadow: 0 1px 6px rgba(201, 154, 36, 0.5);
        }

        /* Smooth Scroll-Triggered Reveal Classes */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 1.15s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 1.15s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-on-scroll.is-revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-slide-left {
            opacity: 0;
            transform: translateX(-36px);
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-slide-left.is-revealed {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-slide-right {
            opacity: 0;
            transform: translateX(36px);
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-slide-right.is-revealed {
            opacity: 1;
            transform: translateX(0);
        }

        .stagger-1 { transition-delay: 0.12s; }
        .stagger-2 { transition-delay: 0.22s; }
        .stagger-3 { transition-delay: 0.32s; }
        .stagger-4 { transition-delay: 0.42s; }
        .stagger-5 { transition-delay: 0.52s; }
        .stagger-6 { transition-delay: 0.62s; }

        /* Sticky Header Elevation on Scroll */
        header {
            position: sticky !important;
            top: 0 !important;
            z-index: 50 !important;
            transition: box-shadow 0.4s ease, background-color 0.4s ease;
        }

        header.is-scrolled {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.28);
        }

        /* Floating Back to Top Button */
        #back-to-top-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 2.85rem;
            height: 2.85rem;
            border-radius: 9999px;
            background-color: #5A0A0D;
            color: #C99A24;
            border: 1.5px solid #C99A24;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transform: translateY(16px) scale(0.9);
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                        visibility 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                        background-color 0.25s ease,
                        color 0.25s ease,
                        box-shadow 0.25s ease;
            z-index: 90;
        }

        #back-to-top-btn.is-visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        #back-to-top-btn:hover {
            background-color: #C99A24;
            color: #5A0A0D;
            border-color: #F4E7C0;
            transform: translateY(-3px) scale(1.06);
            box-shadow: 0 6px 20px rgba(201, 154, 36, 0.4);
        }

        #back-to-top-btn:active {
            transform: translateY(0) scale(0.95);
        }

        /* Animated Search Modal Styles */
        #search-modal {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1),
                        visibility 0.45s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #search-modal.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        #search-modal .search-dialog-card {
            opacity: 0;
            transform: translateY(-26px) scale(0.95);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.5s ease;
            will-change: transform, opacity;
        }

        #search-modal.is-open .search-dialog-card {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        #article-modal {
            visibility: hidden;
            transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                        visibility 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #article-modal.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        #article-modal .article-dialog-card {
            opacity: 0;
            transform: translateY(-20px) scale(0.96);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.4s ease;
            will-change: transform, opacity;
        }

        #article-modal.is-open .article-dialog-card {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .search-result-item {
            transition: background-color 0.22s ease, transform 0.22s ease, border-color 0.22s ease;
        }

        .search-result-item:hover,
        .search-result-item.is-selected {
            background-color: #F8F3EA;
            border-color: #C99A24;
            transform: translateX(4px);
        }

        mark.search-highlight {
            background-color: rgba(212, 160, 23, 0.25);
            color: #7B1113;
            font-weight: 700;
            border-radius: 2px;
            padding: 0 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto !important;
            }
            .reveal-on-scroll,
            .reveal-slide-left,
            .reveal-slide-right {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            #back-to-top-btn {
                transition: none !important;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    @stack('styles')
</head>

<body class="bg-cream-bg text-ink-base antialiased selection:bg-maroon-base selection:text-white overflow-x-clip">

    <!-- Progress Bar -->
    <div id="scroll-progress-bar" aria-hidden="true"></div>

    <!-- Main Content Yield -->
    @yield('content')

    <!-- Floating Back to Top Button -->
    @include('partials.back-to-top')

    <!-- Animated Search Modal -->
    @include('partials.search-modal')

    <!-- Article Viewer Modal -->
    @include('partials.article-modal')

    <!-- Main Application Scripts -->
    <script src="{{ asset('script.js') }}"></script>

    <!-- SweetAlert2 Notifications & Session Handling -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('submission_status') === 'success')
            Swal.fire({
                title: 'Inquiry Received!',
                text: "{{ session('submission_message') }}",
                icon: 'success',
                confirmButtonColor: '#7B1113',
                confirmButtonText: 'Great, thanks!',
                customClass: { popup: 'font-sans rounded-2xl shadow-xl' }
            });
        @elseif(session('submission_status') === 'error')
            Swal.fire({
                title: 'Submission Error',
                text: "{{ session('submission_message', 'Please check your inputs and try again.') }}",
                icon: 'error',
                confirmButtonColor: '#7B1113',
                customClass: { popup: 'font-sans rounded-2xl shadow-xl' }
            });
        @endif

        @if(session('success_message'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Success',
                text: "{{ session('success_message') }}",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                customClass: { popup: 'font-sans' }
            });
        @endif
    });
    </script>
    @stack('scripts')
</body>

</html>
