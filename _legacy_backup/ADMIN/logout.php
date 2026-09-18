<?php
/**
 * Admin Logout
 * UP Cebu TTBDO Admin Portal
 */
require_once __DIR__ . '/../config/sessions.php';

// Destroy all session data
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// If instant redirect requested (e.g. from SweetAlert confirmation)
if (isset($_GET['instant']) && $_GET['instant'] == '1') {
    header('Location: ../index.php?msg=logged_out');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signing Out | UP Cebu TTBDO</title>
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
        .swal2-timer-progress-bar {
            height: 3.5px !important;
            background: #D4A017 !important;
        }
    </style>
    <style type="text/tailwindcss">
        @theme {
            --color-cream-bg: #F3EBDD;
            --color-maroon-base: #7B1113;
            --color-gold-base: #D4A017;
            --color-ink-base: #26231F;

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
    <div class="text-center">
        <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-sm border border-gold-base/50 mb-4">
            <img src="../assets/ttbdo-full-logo.png" alt="UP Cebu TTBDO" class="h-10 w-auto object-contain" />
        </div>
        <p class="text-xs text-ink-base/70">Signing out from TTBDO Administration...</p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'success',
            title: 'Signed Out',
            text: 'You have been safely signed out. Returning to website...',
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
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
            window.location.href = '../index.php?msg=logged_out';
        }, 1400);
    });
    </script>
</body>
</html>
