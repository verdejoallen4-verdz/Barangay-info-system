<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $query = "DELETE FROM residents WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("Location: residents.php?msg=deleted");
        exit;
    } else {
        header("Location: residents.php?msg=error");
        exit;
    }
} else {
    header("Location: residents.php");
    exit;
}