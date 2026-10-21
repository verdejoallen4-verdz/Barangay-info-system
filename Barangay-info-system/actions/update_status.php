<?php
session_start();
require_once '../config/db.php';

// Sigurohon nga Admin ra ang makatandog sa status
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

// Check kung naa bay nadawat nga ID ug Status sa URL
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id     = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    // Validasyon aron limpyo ang status value
    if (in_array($status, ['Approved', 'Rejected', 'Pending'])) {
        $query = "UPDATE requests SET status = '$status' WHERE id = $id";
        mysqli_query($conn, $query);
    }
}

// I-redirect balik sa Admin Requests page para dili mo-white screen
header("Location: " . $base_url . "admin/requests/requests.php");
exit;
?>