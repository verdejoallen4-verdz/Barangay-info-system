<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Information System</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- Home Section -->
<section id="home" class="hero">
    
    <div class="hero-content">
        <a href="/Barangay-info-system/auth/login.php" class="btn-login">Login</a>
        <a href="/Barangay-info-system/auth/register.php" class="btn-register">Register</a>
    </div>

</section>

<!-- About Section -->
<section id="about" class="section-container"><br>
    <h2>Who We Are</h2>
            <p>
                Barangay San Juan is committed to providing accessible,
                reliable, and efficient public services to its residents.
                We aim to maintain a peaceful, organized, and progressive
                community where every resident is valued and supported.
            </p>

            <p>
                Through this Barangay Information System, residents can
                access important community information and learn about
                available barangay services more conveniently.
            </p><br>

            <h3>Our Information System</h3>
            <p>
                The Barangay Information System is an online platform
                designed to make communication between residents and
                barangay officials easier. It helps residents access
                information, submit service requests, and stay updated
                on community announcements.
            </p>

            <p>
                Our goal is to reduce unnecessary visits to the barangay
                hall, improve access to public information, and support
                faster and more organized service delivery.
            </p>
</section>

<!-- Contact Section -->
<section id="contact" class="section-container">
    <h2>Contact Our Barangay</h2>
        <p>
            Have questions or need assistance? Contact Barangay San Juan
            for information about our services, documents, and community updates.
        </p><br>
    </div>

    <div class="contact-container">

        <!-- Contact Information -->
        <div class="contact-info">
            <h3>Contact Information</h3>
            <p class="contact-description">
                You may visit the barangay hall or contact us through the information provided below.
            </p>
                    <h4>📍 Office Address</h4>
                    <p>Barangay Hall, San Juan, Hilongos, Leyte</p>

                    <h4>📧 Email Address</h4>
                    <p>info@barangaysanjuan.gov.ph</p>

                    <h4>📞 Contact Number</h4>
                    <p>0123-456-7890</p>

                    <h4>🕒 Office Hours</h4>
                    <p>Monday to Friday</p>
                    <p>8:00 AM - 5:00 PM</p>

                    <h4> 🏛️ Visit Our Office</h4>
                    <p>For official transactions and personal assistance, visit the barangay hall.</p>
        </div>
</section>

<footer>
    <p>&copy; <?= date('Y') ?> Barangay San Juan, Hilongos, Leyte. All rights reserved.</p>
</footer>

</body>
</html>