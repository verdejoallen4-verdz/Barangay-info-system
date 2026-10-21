<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') { 
    header("Location: ../../auth/login.php"); 
    exit; 
}

$announcements = mysqli_query($conn, "SELECT * FROM announcements ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements Management</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <style>
       
        .btn-add { 
            background: #28a745; 
            color: #fff; 
            padding: 8px 15px; 
            text-decoration: none; 
            border-radius: 4px; 
            display: inline-block; 
            margin-bottom: 15px; 
            font-weight: 600;
        }

        
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 35px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 4px;
            gap: 4px;
            border: none;
            cursor: pointer;
            box-sizing: border-box;
            transition: opacity 0.15s ease-in-out;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #ffffff;
        }

        .btn-action:hover {
            opacity: 0.9;
        }

        
        .announcement-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-left: 4px solid #0088cc;
        }

        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .action-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

    </style>
</head>
<body>
    <?php include '../../includes/sidebar.php'; ?>
    
    <div class="main">
        <h2>Announcements Management</h2>
        
        <a href="add.php" class="btn-add">+ Add Announcement</a>

        <?php if (mysqli_num_rows($announcements) > 0): ?>
            <?php while ($a = mysqli_fetch_assoc($announcements)): ?>
                <br>
                <br>
                <div class="announcement-card">
                    <div class="card-header-flex">
                        <h3 style="margin: 0; color: #0f172a; font-size: 20px; font-weight: 700;"><?= htmlspecialchars($a['title']) ?></h3>
                        
                        <div class="action-group">
                            <a href="edit.php?id=<?= $a['id'] ?>" class="btn-action btn-edit">Edit</a>
                            <a href="delete.php?id=<?= $a['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this announcement?');">Delete</a>
                        </div>
                    </div>
                    <p style="margin-top: 10px; color: #334155; line-height: 1.5; font-size: 15px;"><?= htmlspecialchars($a['content']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #64748b;">No announcements posted yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>