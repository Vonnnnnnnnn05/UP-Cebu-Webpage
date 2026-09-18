<?php
/**
 * Admin Authentication Middleware
 * UP Cebu TTBDO Admin Portal
 */
require_once __DIR__ . '/../config/sessions.php';
require_once __DIR__ . '/../config/conn.php';

// If not logged in, redirect to login page
if (!is_admin_logged_in()) {
    header('Location: login.php');
    exit;
}

$current_admin = current_admin();
