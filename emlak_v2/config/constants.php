<?php
/**
 * Application Constants
 * Emlak Yönetim Sistemi - Global Constants and Settings
 */

// Application Settings
define('APP_NAME', 'Emlak Yönetim Sistemi');
define('APP_VERSION', '2.0.1');
define('APP_ENV', 'development'); // development, production

// Base URL
define('BASE_URL', 'http://localhost/emlak2026/emlak_v2/');
define('ADMIN_URL', BASE_URL . 'admin/');
define('PUBLIC_URL', BASE_URL . 'public/');

// Paths
define('ROOT_PATH', dirname(dirname(__FILE__)) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('ADMIN_PATH', ROOT_PATH . 'admin/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('DATABASE_PATH', ROOT_PATH . 'database/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');
define('UPLOAD_LISTINGS_PATH', UPLOAD_PATH . 'listings/');
define('UPLOAD_PROFILES_PATH', UPLOAD_PATH . 'profiles/');
define('UPLOAD_DOCUMENTS_PATH', UPLOAD_PATH . 'documents/');

// Session Settings
define('SESSION_TIMEOUT', 3600); // 1 saat
define('REMEMBER_ME_TIME', 604800); // 7 gün

// Pagination
define('ITEMS_PER_PAGE', 10);
define('LISTINGS_PER_PAGE', 12);

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('MAX_IMAGE_SIZE', 2097152); // 2MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);

// User Roles
define('ROLE_ADMIN', 'admin');
define('ROLE_OFFICE', 'office');
define('ROLE_AGENT', 'agent');
define('ROLE_CUSTOMER', 'customer');

// Listing Status
define('STATUS_ACTIVE', 'active');
define('STATUS_INACTIVE', 'inactive');
define('STATUS_SOLD', 'sold');
define('STATUS_RENTED', 'rented');

// Property Types
$PROPERTY_TYPES = [
    'apartment' => 'Daire',
    'house' => 'Ev',
    'villa' => 'Villa',
    'shop' => 'Dükkan',
    'office' => 'Ofis',
    'land' => 'Arazi',
    'commercial' => 'Ticari',
    'other' => 'Diğer'
];

// Purpose Types
$PURPOSE_TYPES = [
    'sale' => 'Satış',
    'rent' => 'Kiralama',
    'both' => 'Her İkisi'
];

// Design Themes
$DESIGN_THEMES = [
    'modern' => 'Modern & Minimal',
    'premium' => 'Lüks & Premium',
    'colorful' => 'Renkli & Canlı'
];

// Currency
define('CURRENCY', '₺');
define('CURRENCY_CODE', 'TRY');

// Email Settings
define('MAIL_FROM', 'noreply@emlaksistemi.com');
define('MAIL_FROM_NAME', 'Emlak Yönetim Sistemi');
define('MAIL_HOST', 'localhost');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '');
define('MAIL_PASSWORD', '');

// API Settings
define('API_GOOGLE_MAPS_KEY', '');
define('API_OPENWEATHER_KEY', '');
define('API_OPENAI_KEY', '');

// Social Media
define('WHATSAPP_ENABLED', true);
define('SMS_ENABLED', true);

// Date/Time Format
define('DATE_FORMAT', 'd.m.Y');
define('TIME_FORMAT', 'H:i');
define('DATETIME_FORMAT', 'd.m.Y H:i');

?>
