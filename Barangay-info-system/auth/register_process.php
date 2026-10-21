
<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

$username  = mysqli_real_escape_string($conn, $_POST['username']);
$password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$age       = (int) $_POST['age'];
$purok     = mysqli_real_escape_string($conn, $_POST['purok']);
$contact   = mysqli_real_escape_string($conn, $_POST['contact']);

// Check if username already exists
$check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");

if (mysqli_num_rows($check) > 0) {
    header("Location: register.php?error=" . urlencode("Username is already taken."));
    exit;
}

// Upload profile picture
$image_name = 'default.png';

if (isset($_FILES['profile_image']) &&
    $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {

    $fileTmpPath = $_FILES['profile_image']['tmp_name'];
    $fileName = $_FILES['profile_image']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($fileExtension, $allowedExtensions)) {
        header("Location: register.php?error=" . urlencode("Invalid image format."));
        exit;
    }

    // Correct upload folder
    $uploadFileDir = '../assets/uploads/';

    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true);
    }

    $image_name = time() . '_' . uniqid() . '.' . $fileExtension;
    $uploadFilePath = $uploadFileDir . $image_name;

    if (!move_uploaded_file($fileTmpPath, $uploadFilePath)) {
        header("Location: register.php?error=" . urlencode("Failed to upload profile picture."));
        exit;
    }
}

// Insert into users table
$insert_user = mysqli_query($conn, "
    INSERT INTO users (username, password, role)
    VALUES ('$username', '$password', 'Resident')
");

if ($insert_user) {
    $user_id = mysqli_insert_id($conn);

    // Insert resident information
    $insert_resident = mysqli_query($conn, "
        INSERT INTO residents
        (user_id, full_name, age, purok, contact, profile_image)
        VALUES
        ($user_id, '$full_name', $age, '$purok', '$contact', '$image_name')
    ");

    if ($insert_resident) {
        header("Location: login.php?registered=success");
        exit;
    }

    // Remove the user if resident information could not be saved
    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");

    header("Location: register.php?error=" . urlencode("Failed to save resident information."));
    exit;
}

header("Location: register.php?error=" . urlencode("Registration failed. Please try again."));
exit;
?>
