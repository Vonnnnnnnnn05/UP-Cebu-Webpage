<?php
/**
 * Session Management & Security Helper
 * UP Cebu TTBDO Native Backend
 */

if (session_status() === PHP_SESSION_NONE) {
    // Set secure cookie parameters
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

/**
 * Check if an admin is currently authenticated
 * @return bool
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Get current logged in admin data
 * @return array
 */
function current_admin() {
    return [
        'id' => $_SESSION['admin_id'] ?? null,
        'username' => $_SESSION['admin_username'] ?? '',
        'email' => $_SESSION['admin_email'] ?? '',
        'full_name' => $_SESSION['admin_name'] ?? 'Administrator',
        'role' => $_SESSION['admin_role'] ?? 'editor'
    ];
}

/**
 * Require authentication or redirect to login page
 * @param string $loginUrl
 */
function require_admin_auth($loginUrl = 'login.php') {
    if (!is_admin_logged_in()) {
        header("Location: " . $loginUrl);
        exit;
    }
}
