<?php
if (!isset($base_url)) {
    $base_url = '/Barangay-info-system/';
}

$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';
?>

<!-- HAMBURGER CHECKBOX -->
<input type="checkbox" id="menu-toggle">

<!-- MOBILE HAMBURGER BUTTON -->
<label for="menu-toggle" class="menu-btn" aria-label="Toggle sidebar">
    ☰
</label>

<!-- SIDEBAR -->
<div class="sidebar">

    <img src="<?= $base_url ?>assets/images/sanjuan_logo.png"
         alt="Barangay Logo">

    <h3>BRGY SANJUAN</h3>

    <div class="user-badge"
         style="text-align: center; margin-bottom: 20px; color: #94a3b8; font-size: 12px;">
        <?= strtoupper(htmlspecialchars($role)) ?>
    </div>

    <?php if ($role === 'Admin'): ?>

        <!-- ADMIN LINKS -->
        <a href="<?= $base_url ?>admin/dashboard/dashboard.php"
           class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
            Dashboard
        </a>

        <a href="<?= $base_url ?>admin/officials/officials.php"
           class="<?= $current_page == 'officials.php' ? 'active' : '' ?>">
            Barangay Officials
        </a>

        <a href="<?= $base_url ?>admin/residents/residents.php"
           class="<?= $current_page == 'residents.php' ? 'active' : '' ?>">
            Manage Residents
        </a>

        <a href="<?= $base_url ?>admin/households/households.php"
           class="<?= $current_page == 'households.php' ? 'active' : '' ?>">
            Households
        </a>

        <a href="<?= $base_url ?>admin/requests/requests.php"
           class="<?= ($current_page == 'requests.php' || $current_page == 'print_clearance.php') ? 'active' : '' ?>">
            Requests
        </a>

        <a href="<?= $base_url ?>admin/announcements/announcements.php"
           class="<?= $current_page == 'announcements.php' ? 'active' : '' ?>">
            Announcements
        </a>

    <?php elseif ($role === 'Resident'): ?>

        <!-- RESIDENT LINKS -->
        <a href="<?= $base_url ?>resident/dashboard/dashboard.php"
           class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
            Dashboard
        </a>

        <a href="<?= $base_url ?>resident/profile/profile.php"
           class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">
            My Profile
        </a>

        <a href="<?= $base_url ?>resident/my_request/my_request.php"
           class="<?= ($current_page == 'my_request.php' || $current_page == 'my_request_add.php') ? 'active' : '' ?>">
            My Requests
        </a>

         <a href="<?= $base_url ?>resident/officials/officials.php"
           class="<?= $current_page == 'officials.php' ? 'active' : '' ?>">
            Barangay Officials
        </a>

        <a href="<?= $base_url ?>resident/announcements/announcements.php"
           class="<?= $current_page == 'announcements.php' ? 'active' : '' ?>">
            Announcements
        </a>

    <?php endif; ?>

    <!-- LOGOUT -->
    <a href="<?= $base_url ?>auth/logout.php" class="logout-btn">
        Logout
    </a>

</div>

<!-- DARK OVERLAY -->
<label for="menu-toggle"
       class="menu-overlay"
       aria-label="Close sidebar">
</label>