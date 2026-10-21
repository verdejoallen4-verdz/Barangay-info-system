<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

$query = "SELECT * FROM households ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Households - Admin</title>
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

        /* Horizontal Action Column Layout */
        .action-column {
            display: flex;
            flex-direction: row;
            gap: 6px;
            align-items: center;
        }

        .btn-view, 
        .btn-edit, 
        .btn-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 35px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-sizing: border-box;
            margin: 4px;
        }

        .btn-view { 
            background: #17a2b8; 
            color: #fff; 
        }

        .btn-edit { 
            background: #ffc107; 
            color: #000; 
        }

        .btn-delete { 
            background: #dc3545; 
            color: #fff; 
        }

        .alert { 
            padding: 10px; 
            background: #d4edda; 
            color: #155724; 
            border-radius: 4px; 
            margin-bottom: 15px; 
        }

        
    </style>
</head>
<body>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="main">
        <h2>Manage Households</h2>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert">
                <?php 
                    if ($_GET['msg'] === 'added') echo "Household added successfully!";
                    if ($_GET['msg'] === 'updated') echo "Household updated successfully!";
                    if ($_GET['msg'] === 'deleted') echo "Household deleted successfully!";
                ?>
            </div>
        <?php endif; ?>

        <a href="add.php" class="btn-add">+ Add Household</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Household No.</th>
                    <th>Household Head</th>
                    <th>Purok / Address</th>
                    <th>Total Members</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['household_no'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['head_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['purok'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['total_members'] ?? '1') ?></td>
                    <td>
                        <div class="action-column">
                            <a href="view.php?id=<?= $row['id'] ?>" class="btn-view">View</a>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this household?')">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>