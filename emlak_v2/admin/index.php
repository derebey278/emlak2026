<?php
// Admin Panel
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>