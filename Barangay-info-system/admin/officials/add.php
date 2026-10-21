<?php
session_start();
require_once '../../config/db.php';

// Allow Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $purok    = mysqli_real_escape_string($conn, $_POST['purok']);
    $contact  = mysqli_real_escape_string($conn, $_POST['contact']);

    $query = "INSERT INTO officials (name, position, purok, contact)
              VALUES ('$name', '$position', '$purok', '$contact')";

    if (mysqli_query($conn, $query)) {
        header("Location: officials.php?msg=added");
        exit;
    } else {
        $error = "Error adding record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Official - Admin</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <style>
        .form-container {
            max-width: 500px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .btn-submit,
        .btn-cancel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 38px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            text-decoration: none;
            box-sizing: border-box;
            vertical-align: middle;
        }

        .btn-submit {
            background: #0284c7;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .btn-cancel {
            background: #6c757d;
            color: #fff;
        }
    </style>
</head>

<body>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="main">
        <h2>Add New Barangay Official</h2>

        <?php if (isset($error)): ?>
            <p style="color:red; font-weight:bold; margin-bottom:15px;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <div class="form-container">
            <form action="add.php" method="POST">

                <div class="form-group">
                    <label>Upload Photo:</label>
                    <input type="file" name="official_image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Position:</label>
                    <input type="text" name="position" required>
                </div>

                <div class="form-group">
                    <label>Purok:</label>
                    <input type="text" name="purok" required>
                </div>

                <div class="form-group">
                    <label>Contact Number:</label>
                    <input type="text" name="contact" required>
                </div>

                <button type="submit" class="btn-submit">
                    Save Official
                </button>

                <a href="officials.php" class="btn-cancel">Cancel</a>

            </form>
        </div>
    </div>
</body>
</html>