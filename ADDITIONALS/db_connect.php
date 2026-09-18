<?php
/**
 * ==============================================================================
 * UP CEBU TTBDO - Database Connection & Helper Utilities
 * Driver: MySQLi (Object-Oriented + Procedural Helpers)
 * Configured for standard XAMPP environment
 * ==============================================================================
 */

// Prevent direct re-definition
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'up_cebu_ttbdo_db');
    define('DB_PORT', 3306);
}

// Establish MySQLi connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Check connection
if ($conn->connect_error) {
    die("<div style='font-family:sans-serif;padding:20px;background:#fff1f0;color:#7b1113;border:1px solid #ffa39e;border-radius:8px;max-width:600px;margin:30px auto;'>
        <h3 style='margin-top:0;'>Database Connection Failed</h3>
        <p>Could not connect to MySQL database <strong>" . htmlspecialchars(DB_NAME) . "</strong>.</p>
        <p><small>Error: " . htmlspecialchars($conn->connect_error) . "</small></p>
        <p style='font-size:13px;color:#555;'>Ensure your XAMPP MySQL module is running and that you have imported <code>ADDITIONALS/database.sql</code>.</p>
    </div>");
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

/**
 * Helper 1: Execute a prepared SELECT query and fetch all rows.
 */
function db_fetch_all($db, $sql, $types = "", $params = []) {
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log("MySQL prepare error: " . $db->error);
        return [];
    }

    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    $stmt->close();
    return $data;
}

/**
 * Helper 2: Execute a prepared SELECT query and fetch a single row.
 */
function db_fetch_one($db, $sql, $types = "", $params = []) {
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log("MySQL prepare error: " . $db->error);
        return null;
    }

    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;

    $stmt->close();
    return $row;
}

/**
 * Helper 3: Execute an INSERT, UPDATE, or DELETE query using prepared statements.
 */
function db_execute($db, $sql, $types = "", $params = []) {
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log("MySQL prepare error: " . $db->error);
        return false;
    }

    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $success = $stmt->execute();
    if (!$success) {
        error_log("MySQL execute error: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $insertId = $stmt->insert_id;
    $affected = $stmt->affected_rows;
    $stmt->close();

    if ($insertId > 0) {
        return $insertId;
    }

    return $affected;
}

/**
 * Helper 4: Safe HTML output escaping to prevent XSS
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
