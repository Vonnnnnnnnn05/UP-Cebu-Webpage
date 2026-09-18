<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TTBDO Admin Portal | UP Cebu</title>

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
            --color-cream-bg: #F3EBDD;
            --color-cream-soft: #FBF8F2;
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
</head>
<body class="bg-cream-bg text-ink-base min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 antialiased">

    <!-- Brand Header -->
    <div class="mb-8 text-center">
        <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-3 group">
            <div class="bg-white rounded-2xl p-3 shadow-md border border-border-card transition-transform group-hover:scale-105">
                <img src="{{ asset('assets/ttbdo-full-logo.png') }}" alt="UP Cebu TTBDO Logo" class="h-12 sm:h-14 w-auto object-contain" />
            </div>
            <div>
                <span class="block text-[11px] uppercase tracking-[2px] font-bold text-green-base">
                    UNIVERSITY OF THE PHILIPPINES CEBU
                </span>
                <span class="block font-serif text-xl sm:text-2xl font-bold text-maroon-base leading-tight mt-0.5">
                    Technology Transfer &amp; Business Development Office
                </span>
            </div>
        </a>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-border-card overflow-hidden">
        <div class="bg-maroon-dark text-white p-6 sm:p-8 text-center relative">
            <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-8 h-8 rounded-full bg-gold-base border-4 border-white flex items-center justify-center text-maroon-dark shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            <h1 class="font-serif text-2xl font-bold text-white tracking-tight">
                Staff Administration Portal
            </h1>
            <p class="text-xs text-cream-bg/80 mt-1">
                Authorized Personnel &amp; Office Editors
            </p>
        </div>

        <div class="p-6 sm:p-8 pt-8">
            @if ($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-xl text-red-800 text-xs flex items-center gap-2.5">
                <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <div>
                    {{ $errors->first() }}
                </div>
            </div>
            @endif

            @if (session('success_message'))
            <div class="mb-5 p-3.5 bg-green-50 border border-green-200 rounded-xl text-green-800 text-xs flex items-center gap-2.5">
                <svg class="w-4 h-4 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <div>
                    {{ session('success_message') }}
                </div>
            </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-4" id="admin-login-form">
                @csrf

                <div>
                    <label for="identity" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                        UP Mail / Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-ink-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <input type="text" id="identity" name="identity" required autofocus
                            value="{{ old('identity', 'superadmin@gmail.com') }}"
                            placeholder="admin or user@up.edu.ph"
                            class="w-full pl-10 pr-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                        Security Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-ink-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            value="password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-ink-muted">
                        <input type="checkbox" name="remember" value="1" class="rounded border-border-card text-maroon-base focus:ring-maroon-base">
                        <span>Remember session</span>
                    </label>
                </div>

                <button type="submit" id="login-submit-btn"
                    class="w-full mt-2 py-3 px-4 rounded-xl bg-maroon-base hover:bg-maroon-hover text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span>Login</span>
                    <svg class="w-4 h-4 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-border-card text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-ink-muted hover:text-maroon-base transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Return to Public Portal</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Credentials Hint -->
    <div class="mt-6 text-center text-xs text-ink-muted">
        <span>Default Admin: <strong class="text-maroon-base">superadmin@gmail.com</strong> / password: <strong class="text-maroon-base">password</strong></span>
    </div>

</body>
</html>
