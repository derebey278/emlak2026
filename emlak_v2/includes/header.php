<?php
/**
 * Header Template
 * Top navigation and common header elements
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get user info
$current_user = isset($_SESSION['user_id']) ? get_user($_SESSION['user_id']) : null;
$unread_notifications = $current_user ? get_unread_notifications_count($current_user['id']) : 0;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . APP_NAME : APP_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <i class="fas fa-home"></i> <?php echo APP_NAME; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($current_user): ?>
                        <!-- User Menu -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>listings">
                                <i class="fas fa-list"></i> İlanlar
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>favorites">
                                <i class="fas fa-heart"></i> Favorilerim
                            </a>
                        </li>
                        
                        <?php if (in_array($current_user['role'], ['admin', 'office', 'agent'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>dashboard">
                                    <i class="fas fa-tachometer-alt"></i> Panel
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <?php if ($unread_notifications > 0): ?>
                                    <span class="badge bg-danger"><?php echo $unread_notifications; ?></span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>notifications">
                                    <i class="fas fa-list"></i> Tüm Bildirimler
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>settings/notifications">
                                    <i class="fas fa-cog"></i> Bildirim Ayarları
                                </a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="<?php echo PUBLIC_URL; ?>uploads/profiles/<?php echo $current_user['profile_image'] ?? 'default-avatar.png'; ?>" 
                                     alt="Avatar" class="rounded-circle" width="30" height="30">
                                <?php echo $current_user['full_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile">
                                    <i class="fas fa-user"></i> Profilim
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>settings">
                                    <i class="fas fa-cog"></i> Ayarlar
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout">
                                    <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Guest Menu -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>listings">
                                <i class="fas fa-list"></i> İlanlar
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>login">
                                <i class="fas fa-sign-in-alt"></i> Giriş Yap
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link btn btn-light text-dark" href="<?php echo BASE_URL; ?>register">
                                <i class="fas fa-user-plus"></i> Kayıt Ol
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?> alert-dismissible fade show m-3" role="alert">
            <?php echo $_SESSION['flash_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
    
    <!-- Main Content Container -->
    <main>
