<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    die("Access Denied");
}

$id = $_GET['id'] ?? 0;

$query = "SELECT requests.*, residents.full_name, residents.purok, residents.age 
          FROM requests 
          JOIN residents ON requests.user_id = residents.user_id 
          WHERE requests.id = $id AND requests.status = 'Approved'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Certificate not found or request is not yet approved.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Certificate - <?= htmlspecialchars($data['full_name']) ?></title>
    <style>
        body {
            font-family: 'Georgia', serif;
            padding: 40px;
            max-width: 850px;
            margin: 0 auto;
            border: none;
            box-sizing: border-box;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .logo-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .logo-group img {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
            flex-grow: 1;
            padding: 0 10px;
        }

        .header-text h3 {
            margin: 2px;
            font-weight: normal;
            font-size: 15px;
        }

        .header-text h2 {
            margin: 5px 0;
            text-transform: uppercase;
            font-size: 20px;
        }

        .header-text p {
            margin: 2px;
            font-size: 14px;
        }

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            text-decoration: underline;
            margin: 40px 0;
            text-transform: uppercase;
        }

        .content {
            font-size: 18px;
            line-height: 1.8;
            text-align: justify;
            text-indent: 40px;
        }

        .footer-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 60px;
        }

        .footer {
            text-align: center;
            width: 220px;
        }

        .footer .line {
            border-top: 1px solid #000;
            width: 100%;
            margin-bottom: 5px;
        }

        /* Removes browser headers (date/time/URL) and borders during printing */
        @media print {
            @page {
                margin: 0;
            }
            body {
                border: none !important;
                padding: 20mm;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header-container">
        <div class="logo-group">
            <img src="../assets/images/sanjuan_logo.png" alt="Logo 1">
            <img src="../assets/images/hilongos_logo.png" alt="Logo 2">
        </div>

        <div class="header-text">
            <h3>Republic of the Philippines</h3>
            <h3>Province of Leyte</h3>
            <h2>BARANGAY SAN JUAN</h2>
            <p><i>Office of the Barangay Captain</i></p>
        </div>

        <div class="logo-group">
            <img src="../assets/images/angat_ka_hilongosnon.png" alt="Logo 3">
            <img src="../assets/images/bagong_pilipinas_logo.png" alt="Logo 4">
        </div>
    </div>

    <div class="title"><?= htmlspecialchars($data['document_type']) ?></div>

    <div class="content">
        <p>TO WHOM IT MAY CONCERN:</p>
        <p>
            This is to certify that <b><?= htmlspecialchars($data['full_name']) ?></b>, legal age, residing at Purok <b><?= htmlspecialchars($data['purok']) ?></b>, is a law-abiding citizen and a resident of good moral character in this barangay.
        </p>
        <p>
            This clearance/certificate is being issued upon the request of the above-named person for the purpose of: <b><?= htmlspecialchars($data['purpose']) ?></b>.
        </p>
        <p>
            Issued this <b><?= date('jS') ?></b> day of <b><?= date('F, Y') ?></b>.
        </p>
    </div>

    <div class="footer-container">
        <div class="footer">
            <br><br><br>
            <div class="line"></div>
            <b>PUNONG BARANGAY</b>
        </div>
    </div>

</body>
</html>