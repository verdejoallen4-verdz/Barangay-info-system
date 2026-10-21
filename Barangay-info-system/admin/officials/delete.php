<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $query = "DELETE FROM officials WHERE id=$id";
    mysqli_query($conn, $query);
}

// Redirect back to officials page
header("Location: officials.php?msg=deleted");
exit;
?>