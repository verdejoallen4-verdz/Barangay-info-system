<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') { 
    header("Location: ../../auth/login.php"); 
    exit; 
}

$id = (int)($_GET['id'] ?? 0);
$query = mysqli_query($conn, "SELECT * FROM announcements WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: announcements.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $content = mysqli_real_escape_string($conn, trim($_POST['content'] ?? ''));
    
    if (!empty($title) && !empty($content)) {
        mysqli_query($conn, "UPDATE announcements SET title = '$title', content = '$content' WHERE id = $id");
        header("Location: announcements.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Announcement - Admin</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <style>
        .form-card {
            background: #ffffff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            max-width: 600px;
        }
        form label {
            display: block;
            margin-top: 12px;
            margin-bottom: 6px;
            font-weight: bold;
            color: #334155;
        }
        form input[type="text"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }
        .btn-group {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn-submit {
            background: #0088cc;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <?php include '../../includes/sidebar.php'; ?>
    
    <div class="main">
        <h2>Edit Announcement</h2><br>
        
        <div class="form-card">
            <form method="POST">
                <label>Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" required>
                
                <label>Content</label>
                <input type="text" name="content" value="<?= htmlspecialchars($data['content']) ?>" required>
                
                <div class="btn-group">
                    <button type="submit" class="btn-submit">Update</button>
                    <a href="announcements.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>