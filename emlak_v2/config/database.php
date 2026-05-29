<?php
/**
 * Database Configuration
 * Emlak Yönetim Sistemi - Database Connection Settings
 */

// Database Connection Parameters
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'emlak_db');
define('DB_CHARSET', 'utf8mb4');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Veritabanı Bağlantı Hatası: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset(DB_CHARSET);

// Enable error reporting for development
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

?>
