
<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$result = mysqli_query($conn, "SELECT * FROM officials WHERE id=$id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Official not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Official - Admin</title>
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
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }

        .profile-picture {
            display: block;
            width: 100%;
            max-width: 100%;
            height: auto;
            max-height: 350px;
            object-fit: contain;
            margin: 0 auto 20px;
        }


        .details-item {
            margin-bottom: 12px;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .details-item label {
            font-weight: bold;
            color: #555;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 15px;
        }

        .btn-edit {
            background: #ffc107;
            color: #000;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <?php include '../../includes/sidebar.php'; ?>

    <div class="main">
        <h2>Official Details</h2>

        <div class="details-wrapper">
            <div class="details-card">

                <?php if (!empty($data['official_image'])): ?>
                    <img
                        src="../../assets/uploads/<?= htmlspecialchars($data['official_image']) ?>"
                        alt="Official Profile Picture"
                        class="profile-picture"
                    >
                <?php else: ?>
                    <p style="text-align: center;">No profile picture available.</p>
                <?php endif; ?>

                <div class="details-item">
                    <label>ID:</label>
                    <span><?= htmlspecialchars($data['id']) ?></span>
                </div>

                <div class="details-item">
                    <label>Full Name:</label>
                    <span><?= htmlspecialchars($data['name']) ?></span>
                </div>

                <div class="details-item">
                    <label>Position:</label>
                    <span><?= htmlspecialchars($data['position']) ?></span>
                </div>

                <div class="details-item">
                    <label>Purok / Address:</label>
                    <span><?= htmlspecialchars($data['purok']) ?></span>
                </div>

                <div class="details-item">
                    <label>Contact Number:</label>
                    <span><?= htmlspecialchars($data['contact']) ?></span>
                </div>

                <a href="edit.php?id=<?= $data['id'] ?>" class="btn-edit">
                    Edit Details
                </a>

                <a href="officials.php" class="btn-back">
                    Back to List
                </a>

            </div>
        </div>
    </div>

</body>
</html>
