<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "barangay_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$base_url = "http://localhost/Barangay-info-system/";
?>