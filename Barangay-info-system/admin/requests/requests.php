<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit;
}

$sql = "SELECT requests.*, residents.full_name, residents.purok
        FROM requests
        JOIN residents ON requests.user_id = residents.user_id
        ORDER BY requests.id DESC";

$requests = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Requests - Admin</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

<?php include '../../includes/sidebar.php'; ?>

<div class="main">
    <h2>Resident Document Requests</h2>
    <br>

    <table>
        <thead>
            <tr>
                <th>Resident</th>
                <th>Purok</th>
                <th>Document Type</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($requests && mysqli_num_rows($requests) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($requests)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['purok']) ?></td>
                        <td><?= htmlspecialchars($row['document_type']) ?></td>
                        <td><?= htmlspecialchars($row['purpose']) ?></td>

                        <td>
                            <span class="badge badge-<?= strtolower(htmlspecialchars($row['status'])) ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </td>

                        <td>
                            <?php if ($row['status'] === 'Pending'): ?>

                                <a href="<?= $base_url ?>actions/update_status.php?id=<?= (int) $row['id'] ?>&status=Approved"
                                   class="btn-approve">
                                    Approve
                                </a>

                                <a href="<?= $base_url ?>actions/update_status.php?id=<?= (int) $row['id'] ?>&status=Rejected"
                                   class="btn-reject"
                                   onclick="return confirm('Reject request?')">
                                    Reject
                                </a>

                            <?php elseif ($row['status'] === 'Approved'): ?>

                                <a href="<?= $base_url ?>actions/print.php?id=<?= (int) $row['id'] ?>"
                                   target="_blank"
                                   class="btn-print">
                                    Print
                                </a>

                            <?php else: ?>

                                <!-- <span class="badge badge-rejected">Rejected</span> -->

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">
                        No document requests found.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>