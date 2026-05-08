<!-- app/Views/dashboard/employee.php -->

<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title ?? 'Admin Dashboard');
$totalCards = $data['totalCards'] ?? 0;
$totalEmployees = $data['totalEmployees'] ?? 0;
$totalValue = $data['totalValue'] ?? 0.00;
$recentCards = $data['recentCards'] ?? [];
require_once __DIR__ . '/../common/mainHeader.php';
?>

<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/employeeDashboard.css">

<div class="dashboard-page">

    <!-- TOP SECTION -->
    <div class="dashboard-top">

        <div class="welcome-text">
            <h1>Welcome Back !</h1>
        </div>

        <a href="<?= APP_BASE_URL ?>/inventory/import" class="import-btn">
            ⬇ Import Inventory
        </a>

    </div>

    <!-- STATS -->
    <div class="stats-container">

        <div class="stat-box">
            <h3>Number of cards</h3>
            <p><?= $totalCards ?></p>
        </div>

        <div class="stat-box">
            <h3>Number of Users</h3>
            <p><?= $totalEmployees ?></p>
        </div>

        <div class="stat-box">
            <h3>Total Inventory Value</h3>
            <p>$<?= number_format($totalValue, 2) ?></p>
        </div>

    </div>

    <!-- RECENT CARDS -->
    <div class="cards-grid">

        <?php foreach ($recentCards as $card): ?>

            <div class="card-item">

                <div class="card-image">
                    <img
                        src="<?= APP_BASE_URL ?>/public/uploads/cards/<?= $card['image'] ?>"
                        alt="<?= htmlspecialchars($card['card_name']) ?>">
                </div>

                <div class="card-info">

                    <h4><?= htmlspecialchars($card['card_name']) ?></h4>

                    <p class="price">
                        INSERT PRICE HERE
                    </p>

                    <div class="card-actions">

                        <a href="<?= APP_BASE_URL ?>/cards/edit/<?= $card['card_id'] ?>">
                            ✏
                        </a>

                        <a href="<?= APP_BASE_URL ?>/cards/delete/<?= $card['card_id'] ?>">
                            🗑
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>
