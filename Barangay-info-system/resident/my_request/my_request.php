<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Resident') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$message = "";
$show_form = ($_GET['action'] ?? '') === 'new';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    $document_type = mysqli_real_escape_string($conn, $_POST['document_type']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);

    $query = "INSERT INTO requests (user_id, document_type, purpose, status, requested_at)
              VALUES ($user_id, '$document_type', '$purpose', 'Pending', NOW())";

    if (mysqli_query($conn, $query)) {
        header("Location: my_request.php?status=success");
        exit;
    }

    $message = "Error submitting request. Please try again.";
}

$query = "SELECT * FROM requests WHERE user_id = $user_id ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - Resident Portal</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <style>
        .form-card {
            width: 100%;
            max-width: 500px;
            padding: 24px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        @media (max-width: 480px) {
            .form-card {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

<?php include '../../includes/sidebar.php'; ?>

<div class="main">

    <?php if ($show_form): ?>

        <h2>Request Document</h2>
        <p style="margin-bottom: 20px; color: #64748b;">
            Fill out the details below to request a new certificate.
        </p>

        <?php if ($message): ?>
            <p style="color: #ef4444; margin-bottom: 15px;">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <div class="form-card">
            <form action="my_request.php?action=new" method="POST">
                <div class="form-group">
                    <label for="document_type">Document Type</label>
                    <select name="document_type" id="document_type" class="form-control" required>
                        <option value="Barangay Clearance">Barangay Clearance</option>
                        <option value="Certificate of Indigency">Certificate of Indigency</option>
                        <option value="Business Permit Clearance">Business Permit Clearance</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="purpose">Purpose</label>
                    <input type="text" name="purpose" id="purpose" class="form-control" required>
                </div>

                <button type="submit" name="submit_request" class="btn-primary">
                    Submit Request
                </button>
                <a href="my_request.php" class="btn-secondary">Cancel</a>
            </form>
        </div>

    <?php else: ?>

        <h2>My Document Requests</h2>
        <!-- <p style="margin-bottom: 20px; color: #64748b;">
            Check the status of the documents you requested here.
        </p> -->

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <p style="color: #16a34a; border-radius: 4px; margin-bottom: 15px; padding: 10px; background: #d4edda; margin-bottom: 15px;">
                Request submitted successfully!
            </p>
        <?php endif; ?>

        <a href="my_request.php?action=new" class="btn-primary">
            + Request New Document
        </a><br><br>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DOCUMENT TYPE</th>
                        <th>PURPOSE</th>
                        <th>DATE REQUESTED</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $status = $row['status'] ?? 'Pending';
                            $badge_color = '#eab308';

                            if (strcasecmp($status, 'Approved') === 0) {
                                $badge_color = '#22c55e';
                            } elseif (strcasecmp($status, 'Rejected') === 0) {
                                $badge_color = '#ef4444';
                            }

                            $date = $row['requested_at'] ?? null;
                            ?>
                            <tr>
                                <td>#<?= (int) $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['document_type'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['purpose'] ?? 'N/A') ?></td>
                                <td>
                                    <?= ($date && $date !== '0000-00-00 00:00:00')
                                        ? date('M d, Y', strtotime($date))
                                        : 'N/A' ?>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: <?= $badge_color ?>;">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">
                                No document requests found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>

</body>
</html>