<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') | UP Cebu TTBDO</title>

    <!-- Favicon (Default Laravel Favicon) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: UP Authorized Typefaces -->
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
        div.swal2-container { padding: 1rem !important; }
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
        .swal2-toast .swal2-title { font-size: 0.92rem !important; font-weight: 700 !important; margin: 0 !important; padding: 0 !important; }
        .swal2-toast .swal2-html-container { font-size: 0.84rem !important; margin: 0.2rem 0 0 0 !important; color: #5A554D !important; }
        .swal2-toast .swal2-icon { width: 1.6rem !important; height: 1.6rem !important; margin: 0 0.6rem 0 0 !important; min-width: 1.6rem !important; }
        .swal2-icon { width: 3.4rem !important; height: 3.4rem !important; margin: 0.35rem auto 0.9rem auto !important; transform: scale(0.92); }
        .swal2-icon .swal2-icon-content { font-size: 1.8rem !important; }
        .swal2-title { font-size: 1.2rem !important; font-weight: 700 !important; color: #26231F !important; margin: 0 0 0.45rem 0 !important; padding: 0 !important; line-height: 1.35 !important; }
        .swal2-html-container { font-size: 0.92rem !important; color: #5A554D !important; margin: 0 0 1rem 0 !important; line-height: 1.5 !important; }
        .swal2-actions { margin-top: 0.65rem !important; gap: 0.5rem !important; }
        .swal2-actions button { font-size: 0.88rem !important; font-weight: 600 !important; padding: 0.55rem 1.2rem !important; border-radius: 0.55rem !important; min-height: auto !important; }
        .swal2-timer-progress-bar { height: 3.5px !important; background: #D4A017 !important; }
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
            --color-gold-deep: #8E5E0B;
            --color-cream-bg: #F3EBDD;
            --color-cream-soft: #FBF8F2;
            --color-border-card: #DDD2C2;
            --color-ink-base: #26231F;
            --color-ink-muted: #5A554D;
            --font-heading: "Arsenal", "Libertinus Sans", sans-serif;
            --font-title: "Arsenal", "Libertinus Sans", sans-serif;
            --font-serif: "Arsenal", "Libertinus Sans", sans-serif;
            --font-sans: "Source Sans 3", "Open Sans", Roboto, sans-serif;
            --font-official-serif: "Source Serif 4", "Libre Baskerville", Georgia, serif;
            --font-script: "Style Script", "MonteCarlo", cursive;
            --font-logotype: "Padayon", "Arsenal", sans-serif;
        }
        body { font-family: var(--font-sans); font-size: 0.95rem; line-height: 1.55; }
        h1, h2, h3, h4, h5, h6, .font-heading, .font-title, .font-serif { font-family: var(--font-heading); }
        .swal2-title { font-family: var(--font-heading) !important; }
        .swal2-html-container { font-family: var(--font-sans) !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-cream-bg text-ink-base min-h-screen flex flex-col antialiased">

    <!-- Top Navigation Header -->
    <header class="bg-maroon-dark text-white border-b-2 border-gold-base sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Mark -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                        <div class="bg-white rounded p-1 flex items-center justify-center">
                            <img src="{{ asset('assets/ttbdo-full-logo.png') }}" alt="TTBDO Logo" class="h-7 sm:h-8 w-auto object-contain" />
                        </div>
                        <div class="hidden sm:block">
                            <span class="block text-[10px] uppercase tracking-wider text-gold-light font-bold">ADMINISTRATION PORTAL</span>
                            <span class="block font-serif text-base font-bold leading-tight">UP Cebu TTBDO</span>
                        </div>
                    </a>
                </div>

                <!-- Admin Profile & Quick Links -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank"
                        class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition-colors">
                        <span>View Website</span>
                        <svg class="w-4 h-4 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>

                    @auth('admin')
                    <!-- In-System Feedback Notifications Bell & Dropdown -->
                    <div class="relative" id="notifications-dropdown-container">
                        <button type="button" id="notif-bell-btn" onclick="toggleNotificationsDropdown()"
                            class="relative p-2 rounded-xl text-white/90 hover:text-white hover:bg-white/10 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-gold-base/50"
                            title="Feedback & System Notifications" aria-label="Notifications" aria-expanded="false">
                            <svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>

                            <!-- Unread Badge Indicator -->
                            <span id="notif-badge" class="{{ ($unread_notifications_count ?? 0) > 0 ? '' : 'hidden' }} absolute -top-0.5 -right-0.5 px-1.5 py-0.2 text-[10px] font-black leading-none rounded-full bg-gold-base text-maroon-dark border-2 border-maroon-dark shadow-sm">
                                {{ ($unread_notifications_count ?? 0) > 99 ? '99+' : ($unread_notifications_count ?? 0) }}
                            </span>
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div id="notifications-dropdown"
                            class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white text-ink-base rounded-2xl shadow-2xl border border-border-card z-50 overflow-hidden transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                            
                            <!-- Dropdown Header -->
                            <div class="p-3.5 sm:p-4 border-b border-border-card bg-cream-soft flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-gold-base"></span>
                                    <span class="font-serif text-sm sm:text-base font-bold text-maroon-base">
                                        System Notifications
                                    </span>
                                    <span id="dropdown-unread-pill" class="{{ ($unread_notifications_count ?? 0) > 0 ? '' : 'hidden' }} text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-gold-base/20 text-gold-deep">
                                        {{ $unread_notifications_count ?? 0 }} new
                                    </span>
                                </div>

                                <button type="button" onclick="markAllNotificationsAsRead()"
                                    class="text-[11px] font-bold text-maroon-base hover:text-maroon-hover hover:underline cursor-pointer">
                                    Mark all read
                                </button>
                            </div>

                            <!-- Notification Items List -->
                            <div id="notif-dropdown-list" class="max-h-80 overflow-y-auto divide-y divide-border-card/60 text-xs scrollbar-thin">
                                @if(isset($recent_notifications) && $recent_notifications->count() > 0)
                                    @foreach($recent_notifications as $notif)
                                        @php
                                            $notifData = is_array($notif->data) ? $notif->data : (json_decode($notif->data, true) ?? []);
                                            $isItemUnread = $notif->read_at === null;
                                            $notifItemType = $notifData['item_type'] ?? 'general';
                                            $notifActionUrl = $notifData['action_url'] ?? route('admin.notifications.index');
                                        @endphp
                                        <div id="notif-item-{{ $notif->id }}"
                                            class="p-3.5 transition-colors {{ $isItemUnread ? 'bg-amber-50/60 border-l-4 border-l-gold-base' : 'bg-white hover:bg-cream-soft/50' }} flex items-start justify-between gap-3">
                                            <a href="{{ $notifActionUrl }}" onclick="markNotificationAsRead('{{ $notif->id }}', false)" class="flex-1 block group">
                                                <div class="flex items-center gap-1.5 mb-1">
                                                    <span class="w-2 h-2 rounded-full {{ $isItemUnread ? 'bg-gold-base animate-ping' : 'bg-stone-300' }}"></span>
                                                    <span class="font-bold text-ink-base group-hover:text-maroon-base transition-colors line-clamp-1">
                                                        {{ $notifData['title'] ?? 'Review Feedback' }}
                                                    </span>
                                                </div>
                                                <p class="text-[11.5px] text-ink-muted line-clamp-2 italic pl-3.5 bg-cream-soft/50 rounded py-1 px-1.5 border-l-2 border-amber-300">
                                                    "{{ $notifData['message'] ?? 'View feedback details...' }}"
                                                </p>
                                                <div class="flex items-center justify-between text-[10px] text-ink-muted mt-1.5 pl-3.5">
                                                    <span>From: <strong class="text-ink-base">{{ $notifData['reviewer_name'] ?? 'Director' }}</strong></span>
                                                    <span>{{ $notif->created_at?->diffForHumans() }}</span>
                                                </div>
                                            </a>

                                            @if($isItemUnread)
                                            <button type="button" onclick="markNotificationAsRead('{{ $notif->id }}', true)"
                                                class="p-1 rounded-md text-ink-muted hover:text-emerald-700 hover:bg-emerald-50 transition-colors shrink-0 cursor-pointer"
                                                title="Mark as read">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </button>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 text-center text-ink-muted">
                                        <svg class="w-8 h-8 mx-auto text-stone-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                        </svg>
                                        <p class="font-bold text-xs text-ink-base">All Caught Up!</p>
                                        <p class="text-[11px] text-ink-muted mt-0.5">No new feedback notifications.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Dropdown Footer -->
                            <div class="p-2.5 bg-cream-soft border-t border-border-card text-center">
                                <a href="{{ route('admin.notifications.index') }}"
                                    class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-maroon-base hover:text-maroon-hover transition-colors py-1 px-3 w-full rounded-lg hover:bg-black/5">
                                    <span>View Notifications Center</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    @php
                        $userRole = Auth::guard('admin')->user()->role;
                        $roleBadge = match($userRole) {
                            'director' => 'bg-gold-base text-maroon-dark font-black border border-gold-light',
                            'admin' => 'bg-maroon-light text-white font-bold border border-gold-base/50',
                            default => 'bg-emerald-700 text-white font-bold border border-emerald-400/40',
                        };
                        $roleTitle = match($userRole) {
                            'director' => 'Director (Reviewer & Feedback)',
                            'admin' => 'Administrator (Edit & Approvals)',
                            default => 'Staff (Operations & Content)',
                        };
                    @endphp
                    <div class="flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 rounded-lg bg-maroon-base/90 border border-gold-base/30 text-sm shrink-0 min-w-0" title="{{ $roleTitle }}">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $userRole === 'director' ? 'bg-gold-base animate-pulse' : ($userRole === 'admin' ? 'bg-amber-400' : 'bg-emerald-400') }}"></span>
                        <span class="font-bold text-white text-xs sm:text-sm truncate max-w-[100px] min-[400px]:max-w-[140px] sm:max-w-none">{{ Auth::guard('admin')->user()->full_name }}</span>
                        <span class="text-[9.5px] sm:text-[10px] uppercase tracking-wider px-1.5 sm:px-2 py-0.5 rounded-full shrink-0 {{ $roleBadge }}">
                            {{ strtoupper($userRole) }}
                        </span>
                    </div>

                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="inline shrink-0">
                        @csrf
                        <button type="button" onclick="confirmAdminLogout()"
                            class="inline-flex items-center gap-1.5 px-2.5 sm:px-3.5 py-1.5 rounded-lg bg-red-900/60 hover:bg-red-800 text-white text-sm font-bold transition-colors cursor-pointer shrink-0"
                            title="Sign Out">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                    @endauth
                </div>
            </div>

            <!-- Sub Navigation Bar -->
            <nav class="flex items-center gap-1 sm:gap-2 overflow-x-auto py-1.5 sm:py-2 border-t border-white/10 text-xs sm:text-sm font-medium scrollbar-none" style="-webkit-overflow-scrolling: touch;">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-lg transition-colors flex items-center gap-1.5 sm:gap-2 whitespace-nowrap shrink-0 {{ request()->routeIs('admin.dashboard') ? 'bg-gold-base text-maroon-dark font-bold' : 'text-cream-bg/85 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.news.index') }}"
                    class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-lg transition-colors flex items-center gap-1.5 sm:gap-2 whitespace-nowrap shrink-0 {{ request()->routeIs('admin.news.*') ? 'bg-gold-base text-maroon-dark font-bold' : 'text-cream-bg/85 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                    </svg>
                    <span>News &amp; Research</span>
                </a>

                <a href="{{ route('admin.events.index') }}"
                    class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-lg transition-colors flex items-center gap-1.5 sm:gap-2 whitespace-nowrap shrink-0 {{ request()->routeIs('admin.events.*') ? 'bg-gold-base text-maroon-dark font-bold' : 'text-cream-bg/85 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>Events &amp; Summits</span>
                </a>

                <a href="{{ route('admin.inquiries.index') }}"
                    class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-lg transition-colors flex items-center gap-1.5 sm:gap-2 whitespace-nowrap shrink-0 {{ request()->routeIs('admin.inquiries.*') ? 'bg-gold-base text-maroon-dark font-bold' : 'text-cream-bg/85 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <span>Inquiries</span>
                    @if(isset($pending_inquiries_count) && $pending_inquiries_count > 0)
                    <span class="px-2 py-0.5 rounded-full bg-gold-base text-maroon-dark text-xs font-bold">
                        {{ $pending_inquiries_count }}
                    </span>
                    @endif
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Admin Footer -->
    <footer class="bg-white border-t border-border-card py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-muted">
            <div>
                © {{ date('Y') }} Technology Transfer and Business Development Office • University of the Philippines Cebu
            </div>
            <div class="italic text-gold-deep text-[11px] font-medium">
                Nurtured to Create • Inspired to Innovate • Destined to Serve
            </div>
        </div>
    </footer>

    <!-- SweetAlert2 Admin Logout Script -->
    <script>
    function confirmAdminLogout() {
        Swal.fire({
            title: 'Sign Out?',
            text: 'Are you sure you want to end your administrative session?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#7B1113',
            cancelButtonColor: '#5A554D',
            confirmButtonText: 'Yes, Sign Out',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-2xl shadow-2xl font-sans' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    // Notifications Dropdown & Live Handling
    function toggleNotificationsDropdown() {
        const dropdown = document.getElementById('notifications-dropdown');
        const bellBtn = document.getElementById('notif-bell-btn');
        if (!dropdown) return;

        const isHidden = dropdown.classList.contains('hidden');
        if (isHidden) {
            dropdown.classList.remove('hidden');
            requestAnimationFrame(() => {
                dropdown.classList.remove('opacity-0', 'scale-95');
                dropdown.classList.add('opacity-100', 'scale-100');
            });
            if (bellBtn) bellBtn.setAttribute('aria-expanded', 'true');
        } else {
            closeNotificationsDropdown();
        }
    }

    function closeNotificationsDropdown() {
        const dropdown = document.getElementById('notifications-dropdown');
        const bellBtn = document.getElementById('notif-bell-btn');
        if (!dropdown || dropdown.classList.contains('hidden')) return;

        dropdown.classList.remove('opacity-100', 'scale-100');
        dropdown.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 150);
        if (bellBtn) bellBtn.setAttribute('aria-expanded', 'false');
    }

    document.addEventListener('click', (e) => {
        const container = document.getElementById('notifications-dropdown-container');
        if (container && !container.contains(e.target)) {
            closeNotificationsDropdown();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeNotificationsDropdown();
        }
    });

    function updateNotificationCounters(count) {
        const badge = document.getElementById('notif-badge');
        const navBadge = document.getElementById('nav-notif-badge');
        const pill = document.getElementById('dropdown-unread-pill');

        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
        if (navBadge) {
            if (count > 0) {
                navBadge.textContent = count;
                navBadge.classList.remove('hidden');
            } else {
                navBadge.classList.add('hidden');
            }
        }
        if (pill) {
            if (count > 0) {
                pill.textContent = count + ' new';
                pill.classList.remove('hidden');
            } else {
                pill.classList.add('hidden');
            }
        }
    }

    async function markNotificationAsRead(id, preventRedirect = false) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/admin/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                }
            });
            if (res.ok) {
                const data = await res.json();
                updateNotificationCounters(data.count);
                const item = document.getElementById(`notif-item-${id}`);
                if (item) {
                    item.classList.remove('bg-amber-50/60', 'border-l-4', 'border-l-gold-base');
                    item.classList.add('bg-white');
                    const dot = item.querySelector('.bg-gold-base.animate-ping');
                    if (dot) {
                        dot.classList.remove('bg-gold-base', 'animate-ping');
                        dot.classList.add('bg-stone-300');
                    }
                    const btn = item.querySelector('button[title="Mark as read"]');
                    if (btn) btn.remove();
                }
            }
        } catch (e) {
            console.error('Error marking notification read:', e);
        }
    }

    async function markAllNotificationsAsRead() {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                }
            });
            if (res.ok) {
                updateNotificationCounters(0);
                const list = document.getElementById('notif-dropdown-list');
                if (list) {
                    list.querySelectorAll('[id^="notif-item-"]').forEach(item => {
                        item.classList.remove('bg-amber-50/60', 'border-l-4', 'border-l-gold-base');
                        item.classList.add('bg-white');
                        const dot = item.querySelector('.bg-gold-base.animate-ping');
                        if (dot) {
                            dot.classList.remove('bg-gold-base', 'animate-ping');
                            dot.classList.add('bg-stone-300');
                        }
                        const btn = item.querySelector('button[title="Mark as read"]');
                        if (btn) btn.remove();
                    });
                }
            }
        } catch (e) {
            console.error('Error marking all notifications read:', e);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
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

        @if(session('error_message'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Error',
                text: "{{ session('error_message') }}",
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: { popup: 'font-sans' }
            });
        @endif
    });
    </script>
    @stack('scripts')
</body>
</html>
