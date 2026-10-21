<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT * FROM households WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Household record not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Household Details</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <style>
        .details-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }

        .details-container {
            width: 100%;
            max-width: 500px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px #ddd;
            box-sizing: border-box;
        }

        .details-group {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .details-group label {
            display: block;
            font-weight: bold;
            color: #666;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .details-group p {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .btn-edit-action, .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 38px;
            padding: 0 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-edit-action {
            background: #eab308;
            color: #000;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
        }
    </style>
</head>
<body>

<?php include '../../includes/sidebar.php'; ?>

<div class="main">
    <h2>Manage Households</h2>

    <div class="details-wrapper">
        <div class="details-container">

            <div class="details-group">
                <label>ID:</label>
                <p><?= (int)$data['id'] ?></p>
            </div>

            <div class="details-group">
                <label>Household No.:</label>
                <p><?= htmlspecialchars($data['household_no'] ?? '') ?></p>
            </div>

            <div class="details-group">
                <label>Household Head Name:</label>
                <p><?= htmlspecialchars($data['head_name'] ?? '') ?></p>
            </div>

            <div class="details-group">
                <label>Purok / Address:</label>
                <p><?= htmlspecialchars($data['purok'] ?? '') ?></p>
            </div>

            <div class="details-group">
                <label>Total Members:</label>
                <p><?= htmlspecialchars((string)($data['total_members'] ?? '1')) ?></p>
            </div>

            <a href="edit.php?id=<?= (int)$data['id'] ?>" class="btn-edit-action">Edit Details</a>
            <a href="households.php" class="btn-back">Back to List</a>

        </div>
    </div>
</div>

</body>
</html>