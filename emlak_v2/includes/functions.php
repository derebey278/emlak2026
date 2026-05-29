<?php
/**
 * Helper Functions
 * Emlak Yönetim Sistemi - Common Helper Functions
 */

// Include configuration
require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/config/database.php';

/**
 * Sanitize input data
 */
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8')));
}

/**
 * Validate email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Hash password
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify password
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate unique ID
 */
function generate_id($prefix = '') {
    return $prefix . time() . rand(1000, 9999);
}

/**
 * Format price
 */
function format_price($price, $currency = CURRENCY) {
    return $currency . ' ' . number_format($price, 2, ',', '.');
}

/**
 * Format date
 */
function format_date($date, $format = DATE_FORMAT) {
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function format_datetime($datetime, $format = DATETIME_FORMAT) {
    return date($format, strtotime($datetime));
}

/**
 * Days until date
 */
function days_until($date) {
    $today = new DateTime();
    $target = new DateTime($date);
    $interval = $today->diff($target);
    return $interval->days;
}

/**
 * Check if contract is expiring soon
 */
function is_contract_expiring($end_date, $days = 30) {
    return days_until($end_date) <= $days && days_until($end_date) >= 0;
}

/**
 * Redirect to page
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Upload file
 */
function upload_file($file, $destination, $allowed_types = []) {
    // Check if file exists
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'message' => 'Dosya seçilmedi'];
    }
    
    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Dosya yüklenirken hata oluştu'];
    }
    
    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Dosya çok büyük'];
    }
    
    // Check file type
    if (!empty($allowed_types) && !in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Dosya türü izin verilmemiş'];
    }
    
    // Create destination directory if it doesn't exist
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    // Generate unique filename
    $filename = generate_id('file_') . '_' . basename($file['name']);
    $filepath = $destination . $filename;
    
    // Move file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'filepath' => $filepath];
    }
    
    return ['success' => false, 'message' => 'Dosya kaydedilemedi'];
}

/**
 * Delete file
 */
function delete_file($filepath) {
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Get user info by ID
 */
function get_user($user_id) {
    global $conn;
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    return $result->fetch_assoc();
}

/**
 * Get office info by ID
 */
function get_office($office_id) {
    global $conn;
    $result = $conn->query("SELECT * FROM real_estate_offices WHERE id = $office_id");
    return $result->fetch_assoc();
}

/**
 * Get listing info by ID
 */
function get_listing($listing_id) {
    global $conn;
    $result = $conn->query("SELECT * FROM listings WHERE id = $listing_id");
    return $result->fetch_assoc();
}

/**
 * Check if user is admin
 */
function is_admin($user_id) {
    $user = get_user($user_id);
    return $user && $user['role'] === ROLE_ADMIN;
}

/**
 * Check if user is office owner
 */
function is_office_owner($user_id, $office_id) {
    $office = get_office($office_id);
    return $office && $office['owner_id'] === $user_id;
}

/**
 * Log activity
 */
function log_activity($user_id, $office_id, $action, $entity_type, $entity_id, $description = '') {
    global $conn;
    
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    $stmt = $conn->prepare(
        "INSERT INTO activity_logs (user_id, office_id, action, entity_type, entity_id, description, ip_address, user_agent) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    
    $stmt->bind_param('iissis ss', $user_id, $office_id, $action, $entity_type, $entity_id, $description, $ip_address, $user_agent);
    return $stmt->execute();
}

/**
 * Send notification
 */
function send_notification($user_id, $title, $message, $type = 'info', $entity_type = null, $entity_id = null) {
    global $conn;
    
    $stmt = $conn->prepare(
        "INSERT INTO notifications (user_id, title, message, notification_type, related_entity_type, related_entity_id) 
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    
    $stmt->bind_param('issssi', $user_id, $title, $message, $type, $entity_type, $entity_id);
    return $stmt->execute();
}

/**
 * Get unread notifications count
 */
function get_unread_notifications_count($user_id) {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE user_id = $user_id AND is_read = FALSE");
    $row = $result->fetch_assoc();
    return $row['count'];
}

/**
 * Sanitize filename
 */
function sanitize_filename($filename) {
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
    return $filename;
}

/**
 * Get file size formatted
 */
function format_file_size($size) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $size = max($size, 0);
    $pow = floor(($size ? log($size) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $size /= (1 << (10 * $pow));
    
    return round($size, 2) . ' ' . $units[$pow];
}

/**
 * Pagination helper
 */
function paginate($total_items, $items_per_page = ITEMS_PER_PAGE) {
    $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $total_pages = ceil($total_items / $items_per_page);
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'current_page' => $current_page,
        'total_pages' => $total_pages,
        'total_items' => $total_items,
        'offset' => $offset,
        'limit' => $items_per_page
    ];
}

/**
 * Generate contract number
 */
function generate_contract_number() {
    return 'CNT-' . date('Ymd') . '-' . rand(10000, 99999);
}

/**
 * Get lease duration in months
 */
function get_lease_duration($start_date, $end_date) {
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);
    return ($interval->y * 12) + $interval->m;
}

?>