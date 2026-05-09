<?php

use App\Helpers\SessionManager;
?>
<div class="top-navbar">

    <!-- LEFT -->

    <img src="/sys-dev-lia/public/assets/images/logo.png"
        alt="Other World Games Logo"
        class="logo">


    <!-- RIGHT -->
    <div class="nav-icons">


        <a class="logout-btn" href="/sys-dev-lia/logout">
            Logout
        </a>
    </div>

</div>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">

    <!-- PROFILE -->
    <div class="profile-section">
        <a href="/sys-dev-lia/profile" class="profile-link">
            <i class="bi bi-person-circle"></i>Profile
        </a>

        <div class="profile-name">
            <?= SessionManager::get('profile')['name'] ?><br>
            <small class="text-muted">
                <?= (SessionManager::get('profile')['privilege'] == 1) ? 'Admin' : 'Employee' ?>
            </small>
        </div>
    </div>

    <!-- LINKS -->
    <div class="sidebar-links">
        <a href="/sys-dev-lia/dashboard" class="sidebar-link">
            <i class="bi bi-clipboard-data"></i>
            Dashboard
        </a>
        <a href="/sys-dev-lia/cards" class="sidebar-link">
            <i class="bi bi-box-seam"></i>
            Inventory
        </a>

        <a href="#" class="sidebar-link">
            <i class="bi bi-collection"></i>
            Discover Cards
            <br>
            <small class="text-muted">API</small>
        </a>

        <a href="/sys-dev-lia/reports/inventory" class="sidebar-link">
            <i class="bi bi-file-earmark-text"></i>
            Generate Report
        </a>


    </div>

</div>
