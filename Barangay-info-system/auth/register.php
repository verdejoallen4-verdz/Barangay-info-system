<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Barangay Portal</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="auth-body">
    
    <?php include '../includes/navbar.php'; ?>

    <div class="auth-box">
        <h2 style="text-align: center; margin-bottom: 20px;">Resident Registration</h2>

        <?php if ($error): ?>
            <p style="color: red; margin-bottom: 15px; text-align: center;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="register_process.php" method="POST" enctype="multipart/form-data">
            <label>Full Name</label>
            <input type="text" name="full_name" required>

            <label>Age</label>
            <input type="number" name="age" required>

            <label>Purok</label>
            <input type="text" name="purok" required>

            <label for="contact">Contact Number</label>
            <input
                type="tel"
                name="contact"
                pattern="09[0-9]{9}"
                maxlength="11"
                minlength="11"
                title="Enter 11 digits starting with 09"
                required
            >

            <label>Profile Picture</label>
            <input type="file" name="profile_image" accept="image/*" required>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" style="margin-top: 10px;">Register Account</button>
        </form>

        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Already registered? <a href="login.php">Login Here</a>
        </p>
    </div>
</body>
</html>