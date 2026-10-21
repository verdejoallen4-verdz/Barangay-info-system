<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) die("Invalid resident ID.");

// Get resident
$stmt = mysqli_prepare($conn, "SELECT * FROM residents WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data) die("Resident not found.");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $purok = trim($_POST['purok'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $profile_image = $data['profile_image'] ?? '';
    $new_image = '';

    if ($full_name === '') {
        $error = "Full name is required.";
    } elseif ($age < 1 || $age > 120) {
        $error = "Please enter a valid age.";
    }

    // Upload new picture if selected
    if (!$error && !empty($_FILES['profile_image']['name'])) {
        $tmp = $_FILES['profile_image']['tmp_name'];
        $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

        if ($_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
            $error = "Picture upload failed.";
        } elseif ($_FILES['profile_image']['size'] > 5 * 1024 * 1024) {
            $error = "Picture must not exceed 5 MB.";
        } elseif (!isset($types[mime_content_type($tmp)]) || getimagesize($tmp) === false) {
            $error = "Only valid JPG, PNG, and WEBP pictures are allowed.";
        } else {
            $dir = '../../assets/uploads/';

            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $profile_image = 'resident_' . $id . '_' .
                bin2hex(random_bytes(8)) . '.' . $types[mime_content_type($tmp)];

            $new_image = $dir . $profile_image;

            if (!move_uploaded_file($tmp, $new_image)) {
                $error = "Failed to save picture.";
            }
        }
    }

    // Update resident
    if (!$error) {
        $stmt = mysqli_prepare($conn,
            "UPDATE residents SET full_name=?, age=?, purok=?, contact=?, profile_image=? WHERE id=?"
        );

        mysqli_stmt_bind_param($stmt, "sisssi",
            $full_name, $age, $purok, $contact, $profile_image, $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            header("Location: residents.php?msg=updated");
            exit;
        } else {
            $error = "Error updating resident.";

            if ($new_image && file_exists($new_image)) unlink($new_image);
            mysqli_stmt_close($stmt);
        }
    }

    // Preserve submitted values
    $data['full_name'] = $full_name;
    $data['age'] = $age;
    $data['purok'] = $purok;
    $data['contact'] = $contact;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resident</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <style>
        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px #ddd;
            box-sizing: border-box;
        }

        .form-group { margin-bottom: 15px; }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .btn-submit, .btn-cancel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 38px;
            padding: 0 16px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            box-sizing: border-box;
        }

        .btn-submit {
            background: #0284c7;
            color: #fff;
            cursor: pointer;
        }

        .btn-cancel {
            background: #6c757d;
            color: #fff;
        }

        .error-message { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>

<?php include '../../includes/sidebar.php'; ?>

<div class="main">
    <h2>Edit Resident</h2>

    <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="form-wrapper">
        <div class="form-container">

            <form action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="form-group">
                    <label for="profile_image">Upload New Photo:</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/webp">
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name"
                        value="<?= htmlspecialchars($data['full_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" min="1" max="120"
                        value="<?= htmlspecialchars((string)($data['age'] ?? '')) ?>" required>
                </div>

                <div class="form-group">
                    <label for="purok">Purok / Address:</label>
                    <input type="text" id="purok" name="purok"
                        value="<?= htmlspecialchars($data['purok'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="contact">Contact Number:</label>
                    <input type="text" id="contact" name="contact"
                        value="<?= htmlspecialchars($data['contact'] ?? '') ?>" required>
                </div>

                <button type="submit" class="btn-submit">Update Resident</button>
                <a href="residents.php" class="btn-cancel">Cancel</a>
            </form>

        </div>
    </div>
</div>

</body>
</html>