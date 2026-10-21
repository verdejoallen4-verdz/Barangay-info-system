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
    <title>Login - Barangay Portal</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    
</head>
<body class="auth-body">

    <?php include '../includes/navbar.php'; ?>

    <div class="auth-box">
        <h2 style="text-align: center; margin-bottom: 20px;">Account Login</h2>

        <?php if (isset($_GET['registered'])): ?>
            <p style="color: green; margin-bottom: 15px; text-align: center; font-size: 20px;">Registered successfully! You can now log in.</p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p style="color: red; margin-bottom: 15px; text-align: center;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" style="margin-top: 10px;">Login</button>
        </form>

        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Don't have an account yet? <a href="register.php">Register Here</a>
        </p>
    </div>
</body>
</html>