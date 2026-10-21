<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Resident') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get resident profile image
$user_query = mysqli_query($conn, "SELECT profile_image FROM residents WHERE user_id=$user_id");
$user_data = mysqli_fetch_assoc($user_query);

// Count requests
$my_requests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE user_id=$user_id"))['total'];

$my_approved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE user_id=$user_id AND status='Approved'"))['total'];

$my_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE user_id=$user_id AND status='Pending'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

    <?php include '../../includes/sidebar.php'; ?>

    <div class="main">

        <!-- Profile image and welcome heading -->
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">

            <img
                src="../../assets/uploads/<?= htmlspecialchars($user_data['profile_image'] ?? '') ?>"
                style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;"
            >

            <h2 style="margin-bottom: 0;">
                Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
            </h2>

        </div>

        <!-- Request summary cards -->
        <div class="grid-container">

            <div class="card flex-1">
                <h4>Total Requests</h4>
                <p style="font-size: 32px; font-weight: bold; color: #0284c7;">
                    <?= $my_requests ?>
                </p>
            </div>

            <div class="card flex-1">
                <h4>Pending Requests</h4>
                <p style="font-size: 32px; font-weight: bold; color: #eab308;">
                    <?= $my_pending ?>
                </p>
            </div>

            <div class="card flex-1">
                <h4>Approved Requests</h4>
                <p style="font-size: 32px; font-weight: bold; color: #22c55e;">
                    <?= $my_approved ?>
                </p>
            </div>

        </div>

    </div>

</body>
</html>