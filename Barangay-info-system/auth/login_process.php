<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    // Mo-ana ra ang 'admin123' kung 'Admin' ang role ug 'admin' gyud ang username
    $isAdminDebug = ($password === 'admin123' && $user['role'] === 'Admin' && $username === 'admin');
    $isCorrectPass = password_verify($password, $user['password']);

    if ($isAdminDebug || $isCorrectPass) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        if ($user['role'] === 'Admin') {
            header("Location: ../admin/dashboard/dashboard.php");
        } else {
            header("Location: ../resident/dashboard/dashboard.php");
        }
        exit;
    } else {
        header("Location: login.php?error=Incorrect password!");
        exit;
    }
} else {
    // Gi-fix ang white screen: mo-redirect na sa login page kung walay nakit-an nga username
    header("Location: login.php?error=User not found or invalid username!");
    exit;
}
?>