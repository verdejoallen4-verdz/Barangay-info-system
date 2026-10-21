<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') { 
    header("Location: " . $base_url . "login.php"); 
    exit; 
}

$pending_req  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE status='Pending'"))['total'];
$approved_req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE status='Approved'"))['total'];
$total_res    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM residents"))['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
    <?php include '../../includes/sidebar.php'; ?>
    <div class="main">
        <h2>Admin Dashboard</h2><br>

        <div class="grid-container">
            <div class="card flex-1">
                <h4>Pending Requests</h4>
                <p style="font-size: 32px; font-weight: bold; margin-top: 10px; color: #eab308;"><?= $pending_req ?></p>
            </div>
            <div class="card flex-1">
                <h4>Approved Requests</h4>
                <p style="font-size: 32px; font-weight: bold; margin-top: 10px; color: #22c55e;"><?= $approved_req ?></p>
            </div>
            <div class="card flex-1">
                <h4>Total Residents</h4>
                <p style="font-size: 32px; font-weight: bold; margin-top: 10px; color: #0284c7;"><?= $total_res ?></p>
            </div>
        </div>
    </div>
</body>
</html>