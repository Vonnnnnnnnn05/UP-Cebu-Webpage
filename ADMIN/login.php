<?php
/**
 * Admin Authentication Portal
 * UP Cebu TTBDO
 */
require_once __DIR__ . '/../config/sessions.php';
require_once __DIR__ . '/../config/conn.php';

// Redirect if already logged in
if (is_admin_logged_in()) {
    header('Location: index.php');
    exit;
}

$error_message = '';
$success_message = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $success_message = 'You have been safely signed out.';
}

// Process Login Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = trim($_POST['identity'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $is_ajax  = isset($_POST['ajax_login']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

    if (empty($identity) || empty($password)) {
        $error_message = 'Please enter both your email/username and password.';
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $error_message]);
            exit;
        }
    } else {
        // Query admin by email or username using prepared statement
        $admin = db_fetch_one($conn, "SELECT * FROM admins WHERE email = ? OR username = ? LIMIT 1", "ss", [$identity, $identity]);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Set session variables
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_email']    = $admin['email'];
            $_SESSION['admin_name']     = $admin['full_name'];
            $_SESSION['admin_role']     = $admin['role'];

            // Update last_login timestamp
            db_execute($conn, "UPDATE admins SET last_login = NOW() WHERE id = ?", "i", [$admin['id']]);

            $admin_dashboard_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/index.php';

            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'redirect' => $admin_dashboard_url,
                    'admin_name' => $admin['full_name']
                ]);
                exit;
            }

            header('Location: ' . $admin_dashboard_url);
            exit;
        } else {
            $error_message = 'Invalid credentials. Please verify your email/username and password.';
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $error_message]);
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTBDO Admin Portal | UP Cebu</title>
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
            font-family: var(--font-heading) !important;
            font-size: 0.92rem !important;
            font-weight: 700 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .swal2-toast .swal2-html-container {
            font-family: var(--font-sans) !important;
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
            font-family: var(--font-heading) !important;
            font-size: 1.2rem !important;
            font-weight: 700 !important;
            color: #26231F !important;
            margin: 0 0 0.45rem 0 !important;
            padding: 0 !important;
            line-height: 1.35 !important;
        }
        .swal2-html-container {
            font-family: var(--font-sans) !important;
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
            --color-gold-base: #D4A017;
            --color-gold-light: #F4E7C0;
            --color-cream-bg: #F3EBDD;
            --color-cream-soft: #FBF8F2;
            --color-ink-base: #26231F;
            --color-ink-muted: #5A554D;
            --color-border-card: #DDD2C2;

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
        }
        h1, h2, h3, h4, h5, h6, .font-heading, .font-title, .font-serif {
            font-family: var(--font-heading);
        }
    </style>
</head>
<body class="bg-cream-bg min-h-screen flex items-center justify-center p-4 antialiased text-ink-base">

    <div class="w-full max-w-md">
        <!-- Logo and Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center p-3.5 bg-white rounded-2xl shadow-sm border border-gold-base/50 mb-3.5">
                <img src="../assets/ttbdo-full-logo.png" alt="UP Cebu TTBDO" class="h-11 w-auto object-contain" />
            </div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base tracking-tight">Staff Management Portal</h1>
            <p class="text-sm text-ink-muted mt-1.5">Technology Transfer and Business Development Office</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white border border-border-card rounded-2xl shadow-md p-6 sm:p-8">
            <div class="mb-6 pb-4 border-b border-border-card flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-maroon-base uppercase tracking-wider">Sign In</h2>
                    <span class="text-xs text-ink-muted">Authorized TTBDO Personnel Only</span>
                </div>
                <span class="w-9 h-9 rounded-full bg-maroon-base/10 text-maroon-base flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </span>
            </div>

            <?php if (!empty($error_message)): ?>
            <div class="mb-4 p-3.5 rounded-xl bg-maroon-base/10 border border-maroon-base/20 flex items-center gap-3 text-maroon-base text-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 8.25h.008v.008H12v-.008Z" />
                </svg>
                <span><?= e($error_message) ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
            <div class="mb-4 p-3.5 rounded-xl bg-green-base/10 border border-green-base/20 flex items-center gap-3 text-green-base text-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span><?= e($success_message) ?></span>
            </div>
            <?php endif; ?>

            <form id="admin-login-form" action="login.php" method="POST" class="space-y-4">
                <div>
                    <label for="identity" class="block text-sm font-semibold text-ink-base mb-1.5">
                        Email Address or Username
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-ink-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </span>
                        <input type="text" id="identity" name="identity" required
                            value="<?= e($_POST['identity'] ?? 'superadmin@gmail.com') ?>"
                            placeholder="superadmin@gmail.com"
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-ink-base mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-ink-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-border-card bg-cream-soft/40 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base transition-all">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-maroon-base hover:bg-maroon-hover text-white text-sm font-bold tracking-wide uppercase shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98">
                        <span>Authenticate</span>
                        <svg class="w-4 h-4 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-border-card text-center text-sm text-ink-muted">
                <a href="../index.php" class="inline-flex items-center gap-1.5 text-maroon-base hover:text-green-base font-semibold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Return to Public Website</span>
                </a>
            </div>
        </div>

        <p class="text-center text-xs text-ink-muted mt-6">
            © <?= date('Y') ?> University of the Philippines Cebu • TTBDO
        </p>
    </div>

    <!-- SweetAlert2 Script -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Show Logout Toast if redirected with msg=logged_out
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('msg') === 'logged_out') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Signed Out',
                text: 'You have been safely signed out.',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInRight animate__faster'
                }
            });
        }

        // Intercept login form submission with SweetAlert2
        const form = document.getElementById('admin-login-form');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const identity = document.getElementById('identity').value.trim();
                const password = document.getElementById('password').value.trim();

                if (!identity || !password) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Required Information',
                        text: 'Please enter both your email/username and password.',
                        confirmButtonColor: '#7B1113',
                        showClass: {
                            popup: 'animate__animated animate__headShake animate__faster'
                        },
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl font-sans'
                        }
                    });
                    return;
                }

                // Show SweetAlert2 loading state
                Swal.fire({
                    title: 'Authenticating...',
                    text: 'Checking your administrative credentials',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl font-sans'
                    }
                });

                try {
                    const formData = new FormData();
                    formData.append('identity', identity);
                    formData.append('password', password);
                    formData.append('ajax_login', '1');

                    const response = await fetch('login.php', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const data = await response.json();

                    if (data && data.success) {
                        const adminName = data.admin_name || 'Admin';
                        Swal.fire({
                            icon: 'success',
                            title: `Welcome back, ${adminName}!`,
                            text: 'Authentication successful. Redirecting to dashboard...',
                            timer: 1600,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            showClass: {
                                popup: 'animate__animated animate__zoomIn animate__faster'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__zoomOut animate__faster'
                            },
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl font-sans'
                            }
                        });
                        setTimeout(() => {
                            window.location.href = data.redirect || 'index.php';
                        }, 1400);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sign In Failed',
                            text: (data && data.message) ? data.message : 'Invalid credentials. Please verify your email/username and password.',
                            confirmButtonColor: '#7B1113',
                            confirmButtonText: 'Try Again',
                            showClass: {
                                popup: 'animate__animated animate__shakeX animate__faster'
                            },
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl font-sans'
                            }
                        });
                    }
                } catch (err) {
                    form.submit();
                }
            });
        }
    });
    </script>

</body>
</html>
