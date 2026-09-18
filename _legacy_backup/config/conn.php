<?php
/**
 * Database Connection & Query Helpers
 * UP Cebu TTBDO
 */

// Establish MySQLi connection
$db_name = "up_cebu_ttbdo_db";
$conn = @mysqli_connect("localhost", "root", "", $db_name);

// Fallback to up_cebu_ttbdo_db if up is unavailable
if (!$conn) {
    $db_name = "up_cebu_ttbdo_db";
    $conn = @mysqli_connect("localhost", "root", "", $db_name);
}

if (!$conn) {
    die("<div style='font-family:sans-serif;padding:20px;background:#fff1f0;color:#7b1113;border:1px solid #ffa39e;border-radius:8px;max-width:600px;margin:30px auto;'>
        <h3 style='margin-top:0;'>Database Connection Failed</h3>
        <p>Could not connect to MySQL database.</p>
        <p><small>Error: " . htmlspecialchars(mysqli_connect_error()) . "</small></p>
    </div>");
}

mysqli_set_charset($conn, "utf8mb4");

// Ensure helpers are available
if (!function_exists('db_fetch_all')) {
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
}

if (!function_exists('db_fetch_one')) {
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
}

if (!function_exists('db_execute')) {
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

        return ($insertId > 0) ? $insertId : $affected;
    }
}

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}