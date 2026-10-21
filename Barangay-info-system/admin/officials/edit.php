<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . $base_url . "auth/login.php");
    exit;
}

$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    die("Invalid official ID.");
}

// Get official information
$stmt = mysqli_prepare($conn, "SELECT * FROM officials WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Official not found.");
}

$error = "";

// Update official information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $purok = trim($_POST['purok'] ?? '');
    $contact = trim($_POST['contact'] ?? '');

    // Keep the existing picture if no new picture is selected
    $official_image = $data['official_image'] ?? '';
    $new_image_path = '';

    if ($name === '') {
        $error = "Full name is required.";
    } elseif ($position === '') {
        $error = "Position is required.";
    } elseif ($purok === '') {
        $error = "Purok / Address is required.";
    } elseif ($contact === '') {
        $error = "Contact number is required.";
    }

    // Upload a new profile picture if selected
    if (
        $error === '' &&
        isset($_FILES['official_image']) &&
        $_FILES['official_image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES['official_image']['error'] !== UPLOAD_ERR_OK) {
            $error = "There was a problem uploading the picture.";
        } else {
            $tmp_name = $_FILES['official_image']['tmp_name'];
            $file_size = $_FILES['official_image']['size'];

            $allowed_types = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            $file_type = mime_content_type($tmp_name);

            if (!isset($allowed_types[$file_type])) {
                $error = "Only JPG, PNG, and WEBP pictures are allowed.";
            } elseif ($file_size > 5 * 1024 * 1024) {
                $error = "Picture size must not exceed 5 MB.";
            } elseif (getimagesize($tmp_name) === false) {
                $error = "Please select a valid image.";
            } else {
                $upload_dir = '../../assets/uploads/';

                if (
                    !is_dir($upload_dir) &&
                    !mkdir($upload_dir, 0755, true) &&
                    !is_dir($upload_dir)
                ) {
                    $error = "Unable to create the upload folder.";
                } else {
                    $extension = $allowed_types[$file_type];

                    $new_filename = 'official_' . $id . '_' .
                        bin2hex(random_bytes(8)) . '.' . $extension;

                    $new_image_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($tmp_name, $new_image_path)) {
                        $official_image = $new_filename;
                    } else {
                        $error = "Failed to save the picture.";
                    }
                }
            }
        }
    }

    // Update database
    if ($error === '') {
        $update = mysqli_prepare(
            $conn,
            "UPDATE officials
             SET name = ?, position = ?, purok = ?,
                 contact = ?, official_image = ?
             WHERE id = ?"
        );

        if (!$update) {
            $error = "Unable to prepare the update.";
        } else {
            mysqli_stmt_bind_param(
                $update,
                "sssssi",
                $name,
                $position,
                $purok,
                $contact,
                $official_image,
                $id
            );

            if (mysqli_stmt_execute($update)) {
                mysqli_stmt_close($update);

                // Delete the old picture after a successful update
                if ($new_image_path !== '') {
                    $old_image = $data['official_image'] ?? '';

                    if ($old_image !== '') {
                        $old_image_path = '../../assets/uploads/' .
                            basename($old_image);

                        if (is_file($old_image_path)) {
                            unlink($old_image_path);
                        }
                    }
                }

                header("Location: officials.php?msg=updated");
                exit;
            } else {
                $error = "Error updating official information.";

                // Remove new picture if database update fails
                if (
                    $new_image_path !== '' &&
                    file_exists($new_image_path)
                ) {
                    unlink($new_image_path);
                }

                mysqli_stmt_close($update);
            }
        }
    }

    // Preserve submitted values if an error occurs
    $data['name'] = $name;
    $data['position'] = $position;
    $data['purok'] = $purok;
    $data['contact'] = $contact;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Official - Admin</title>

    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <style>
        .form-container {
            max-width: 500px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

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

        .current-picture {
            display: block;
            max-width: 100%;
            max-height: 200px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin-bottom: 10px;
        }

        /* Keep original button sizes and alignment */
        .btn-submit,
        .btn-cancel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 38px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            text-decoration: none;
            box-sizing: border-box;
            vertical-align: middle;
        }

        .btn-submit {
            background: #0284c7;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .btn-cancel {
            background: #6c757d;
            color: #fff;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <?php include '../../includes/sidebar.php'; ?>

    <div class="main">

        <h2>Edit Official</h2>

        <?php if ($error !== ''): ?>
            <p class="error-message">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <div class="form-container">

            <form
                action="edit.php?id=<?= (int)$id ?>"
                method="POST"
                enctype="multipart/form-data"
            >
                <input type="hidden" name="id" value="<?= (int)$data['id'] ?>">

                <div class="form-group">

                <div class="form-group">
                    <label for="official_image">Upload New Photo:</label>
                    <input
                        type="file"
                        id="official_image"
                        name="official_image"
                        accept="image/jpeg,image/png,image/webp"
                    >
                </div>

                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?= htmlspecialchars($data['name'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="position">Position:</label>
                    <input
                        type="text"
                        id="position"
                        name="position"
                        value="<?= htmlspecialchars($data['position'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="purok">Purok / Address:</label>
                    <input
                        type="text"
                        id="purok"
                        name="purok"
                        value="<?= htmlspecialchars($data['purok'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="contact">Contact Number:</label>
                    <input
                        type="text"
                        id="contact"
                        name="contact"
                        value="<?= htmlspecialchars($data['contact'] ?? '') ?>"
                        required
                    >
                </div>

                <button type="submit" class="btn-submit">
                    Update Official
                </button>

                <a href="officials.php" class="btn-cancel">
                    Cancel
                </a>

            </form>
        </div>
    </div>

</body>
</html>