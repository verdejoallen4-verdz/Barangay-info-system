<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Invalid resident ID.");
}

$stmt = mysqli_prepare($conn, "SELECT * FROM residents WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Resident not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Details</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <style>
        .details-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }

        .details-card {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px #ddd;
            box-sizing: border-box;
        }

        .profile-picture {
            display: block;
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            margin: 0 auto 20px;
        }

        .details-item {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }

        .details-item label {
            font-weight: bold;
            color: #555;
        }

        .details-item p {
            margin-top: 5px;
            font-size: 15px;
            color: #333;
            overflow-wrap: anywhere;
        }

        .btn-edit, .btn-back {
            display: inline-block;
            padding: 8px 15px;
            margin-top: 15px;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-edit {
            background: #ffc107;
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
    <h2>Resident Details</h2>

    <div class="details-wrapper">
        <div class="details-card">

            <?php if (!empty($data['profile_image'])): ?>
                <img
                    src="../../assets/uploads/<?= rawurlencode(basename($data['profile_image'])) ?>?v=<?= time() ?>"
                    alt="Resident Profile Picture"
                    class="profile-picture"
                >
            <?php else: ?>
                <p>No profile picture available.</p>
            <?php endif; ?>

            <div class="details-item">
                <label>ID:</label>
                <p><?= (int)$data['id'] ?></p>
            </div>

            <div class="details-item">
                <label>Full Name:</label>
                <p><?= htmlspecialchars($data['full_name'] ?? $data['name'] ?? 'N/A') ?></p>
            </div>

            <div class="details-item">
                <label>Age:</label>
                <p><?= htmlspecialchars((string)($data['age'] ?? 'N/A')) ?></p>
            </div>

            <div class="details-item">
                <label>Purok / Address:</label>
                <p><?= htmlspecialchars($data['purok'] ?? $data['address'] ?? 'N/A') ?></p>
            </div>

            <div class="details-item">
                <label>Contact Number:</label>
                <p><?= htmlspecialchars($data['contact'] ?? 'N/A') ?></p>
            </div>

            <a href="edit.php?id=<?= (int)$data['id'] ?>" class="btn-edit">Edit Details</a>
            <a href="residents.php" class="btn-back">Back to List</a>

        </div>
    </div>
</div>

</body>
</html>