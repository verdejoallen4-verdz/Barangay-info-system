<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = intval($_POST['id']);
    $household_no  = mysqli_real_escape_string($conn, $_POST['household_no']);
    $head_name     = mysqli_real_escape_string($conn, $_POST['head_name']);
    $purok         = mysqli_real_escape_string($conn, $_POST['purok']);
    $total_members = intval($_POST['total_members']);

    $query = "UPDATE households SET household_no='$household_no', head_name='$head_name', purok='$purok', total_members=$total_members WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header("Location: households.php?msg=updated");
        exit;
    } else {
        $error = "Error updating household: " . mysqli_error($conn);
    }
}

$result = mysqli_query($conn, "SELECT * FROM households WHERE id=$id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Household record not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Household - Admin</title>
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
        <h2>Edit Household</h2>

        <?php if (isset($error)): ?>
            <p style="color:red;"><?= $error ?></p>
        <?php endif; ?>

        <div class="form-container">
            <form action="edit.php?id=<?= $id ?>" method="POST">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                
                <div class="form-group">
                    <label>Household No.:</label>
                    <input type="text" name="household_no" value="<?= htmlspecialchars($data['household_no'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Household Head Name:</label>
                    <input type="text" name="head_name" value="<?= htmlspecialchars($data['head_name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Purok / Address:</label>
                    <input type="text" name="purok" value="<?= htmlspecialchars($data['purok'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Total Members:</label>
                    <input type="number" name="total_members" value="<?= htmlspecialchars($data['total_members'] ?? '1') ?>" required>
                </div>
                <button type="submit" class="btn-submit">Update Household</button>
                <a href="households.php" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>