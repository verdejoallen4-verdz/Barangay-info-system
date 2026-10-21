<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Resident') {
    header("Location: ../../auth/login.php");
    exit;
}

$query = "SELECT * FROM officials
          ORDER BY
          CASE position
              WHEN 'Captain' THEN 1
              WHEN 'Barangay Captain' THEN 1
              WHEN 'Kagawad' THEN 2
              WHEN 'Barangay Kagawad' THEN 2
              WHEN 'SK Chairperson' THEN 3
              WHEN 'Secretary' THEN 4
              WHEN 'Barangay Secretary' THEN 4
              WHEN 'Treasurer' THEN 5
              WHEN 'Barangay Treasurer' THEN 5
              ELSE 6
          END, name ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Officials</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <style>
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .officials-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .officials-table th {
            background: #0f172a;
            color: white;
            padding: 14px;
            text-align: left;
        }

        .officials-table td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .officials-table tr:last-child td {
            border-bottom: none;
        }

        .official-photo {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<?php include '../../includes/sidebar.php'; ?>

<div class="main">
    <h2>Barangay Officials</h2>

    <div class="table-wrapper">
        <table class="officials-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Full Name</th>
                    <th>Position</th>
                    <th>Purok / Address</th>
                    <th>Contact Number</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($official = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($official['id']) ?></td>
                            <td>
                                <?php if (!empty($official['official_image'])): ?>
                                    <img
                                        class="official-photo"
                                        src="../../assets/uploads/<?= htmlspecialchars($official['official_image']) ?>"
                                        alt="Official photo"
                                    >
                                <?php else: ?>
                                    No photo
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($official['name']) ?></td>
                            <td><?= htmlspecialchars($official['position']) ?></td>
                            <td><?= htmlspecialchars($official['purok']) ?></td>
                            <td><?= htmlspecialchars($official['contact']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            No barangay officials found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>