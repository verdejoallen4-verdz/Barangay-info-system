<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Resident') { 
    header("Location: ../../auth/login.php"); 
    exit; 
}

$announcements = mysqli_query($conn, "SELECT * FROM announcements ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Announcements</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <style>
        /* MATCHED CARD & FEED LAYOUT FROM ADMIN */
        .announcement-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-left: 4px solid #0088cc;
        }

        .announcement-title {
            margin: 0;
            color: #0f172a;
            font-size: 20px;
            font-weight: 700;
        }

        .announcement-content {
            margin-top: 10px;
            color: #334155;
            font-size: 15px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <?php include '../../includes/sidebar.php'; ?>
    
    <div class="main">
        <h2>Barangay Announcements</h2>

        <div style="margin-top: 20px;">
            <?php if (mysqli_num_rows($announcements) > 0): ?>
                <?php while ($a = mysqli_fetch_assoc($announcements)): ?>
                    <div class="announcement-card">
                        <!-- LARGER TITLE MATCHING ADMIN CARD HEADER -->
                        <h3 class="announcement-title"><?= htmlspecialchars($a['title']) ?></h3>
                        
                        <!-- BODY CONTENT ONLY (DATE & TIME REMOVED) -->
                        <p class="announcement-content"><?= htmlspecialchars($a['content']) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #64748b;">No announcements posted yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>