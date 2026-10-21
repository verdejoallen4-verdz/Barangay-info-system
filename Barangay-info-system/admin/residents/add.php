<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $age       = intval($_POST['age']);
    $purok     = mysqli_real_escape_string($conn, $_POST['purok']);
    $contact   = mysqli_real_escape_string($conn, $_POST['contact']);

    $query = "INSERT INTO residents (full_name, age, purok, contact) VALUES ('$full_name', $age, '$purok', '$contact')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: residents.php?msg=added");
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
    <title>Add Resident - Admin</title>
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

        /* Fixed Button Heights & Alignment */
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
        <h2>Add New Resident</h2>

        <?php if (isset($error)): ?>
            <p style="color:red;"><?= $error ?></p>
        <?php endif; ?>

        <div class="form-container">
            <form action="add.php" method="POST">

                <div class="form-group">
                    <label>Upload Photo:</label>
                    <input type="file" name="official_image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="full_name" required>
                </div>

                <div class="form-group">
                    <label>Age:</label>
                    <input type="number" name="age" required>
                </div>
                
                <div class="form-group">
                    <label>Purok / Address:</label>
                    <input type="text" name="purok" required>
                </div>

                <div class="form-group">
                    <label>Contact Number:</label>
                    <input type="text" name="contact" required>
                </div>
                
                <button type="submit" class="btn-submit">Save Resident</button>
                <a href="residents.php" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>