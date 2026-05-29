<?php
/**
 * Database Configuration
 * Emlak Yönetim Sistemi
 */

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'emlak_system');
define('DB_PORT', 3306);

// Connection Options
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

// Application Settings
define('APP_NAME', 'Emlak Ofisi Yönetim Sistemi');
define('APP_URL', 'http://localhost/emlak2026');
define('APP_ENV', 'development'); // production, development, testing

// JWT Secret
define('JWT_SECRET', 'your-super-secret-jwt-key-change-in-production');

// Google Maps API
define('GOOGLE_MAPS_API_KEY', 'YOUR_GOOGLE_MAPS_API_KEY');

// AI Integration
define('OPENAI_API_KEY', 'YOUR_OPENAI_API_KEY');
define('CLAUDE_API_KEY', 'YOUR_CLAUDE_API_KEY');

// SMS/WhatsApp Integration
define('TWILIO_ACCOUNT_SID', 'YOUR_TWILIO_ACCOUNT_SID');
define('TWILIO_AUTH_TOKEN', 'YOUR_TWILIO_AUTH_TOKEN');
define('TWILIO_PHONE', '+1234567890');

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('SMTP_FROM', 'noreply@emlaksistemi.com');

// Database Connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Veritabanı Bağlantı Hatası: ' . $e->getMessage());
}

?>
