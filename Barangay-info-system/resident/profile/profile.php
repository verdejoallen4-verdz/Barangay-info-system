<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Resident') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $purok = mysqli_real_escape_string($conn, $_POST['purok']);

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "../../assets/uploads/";
        $file_name = time() . '_' . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $query_img = "UPDATE residents SET profile_image='$file_name' WHERE user_id='$user_id'";
            mysqli_query($conn, $query_img);
        }
    }

    $query = "UPDATE residents SET full_name='$full_name', age='$age', contact='$contact', purok='$purok' WHERE user_id='$user_id'";

    if (mysqli_query($conn, $query)) {
        $msg = "Profile updated successfully!";
    }
}

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM residents WHERE user_id='$user_id'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

<?php include '../../includes/sidebar.php'; ?>

<div class="main">

    <!-- Profile image and heading -->
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
        <img
            src="../../assets/uploads/<?= htmlspecialchars($user['profile_image'] ?? '') ?>"
            style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;"
        >

        <h2 style="margin-bottom: 0;">My Profile</h2>
    </div>

    <!-- Profile form -->
    <div class="card" style="max-width: 500px; margin: auto;">

        <?php
        if (isset($msg)) {
            echo "<p style='color: green; font-size: 20px;'>$msg</p>";
        }
        ?>

        <form method="POST" enctype="multipart/form-data">

            <label for="profile_image">Profile Picture</label>
            <input
                type="file"
                id="profile_image"
                name="profile_image"
                accept="image/jpeg,image/png,image/webp"
            >

            <label>Full Name</label>
            <input
                type="text"
                name="full_name"
                value="<?= htmlspecialchars($user['full_name'] ?? '') ?>"
                required
            >

            <label>Age</label>
            <input
                type="number"
                name="age"
                value="<?= htmlspecialchars($user['age'] ?? '') ?>"
                required
            >

            <label>Contact Number</label>
            <input
                type="text"
                name="contact"
                value="<?= htmlspecialchars($user['contact'] ?? '') ?>"
                required
            >

            <label>Purok</label>
            <input
                type="text"
                name="purok"
                value="<?= htmlspecialchars($user['purok'] ?? '') ?>"
                required
            >

            <button type="submit" class="btn-primary">Update Profile</button>

        </form>
    </div>

</div>

</body>
</html>