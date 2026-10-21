<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if ($_SESSION['role'] === 'Admin') {
    header("Location: admin/dashboard/dashboard.php");
} else {
    header("Location: resident/dashboard/dashboard.php"); // Gidugangan og /dashboard/
}
exit;

?>