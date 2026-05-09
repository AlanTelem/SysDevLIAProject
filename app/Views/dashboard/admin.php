<!-- app/Views/dashboard/employee.php -->

<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title ?? 'Dashboard');
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
        <a href="<?= APP_BASE_URL ?>/employees" class="import-btn">
            <i class="bi bi-list-stars"></i>
            Employee List
        </a>
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

            <a
                href="<?= APP_BASE_URL ?>/cards/<?= $card['card_id'] ?>"
                class="card-link">
                <div class="card-item">
                    <div class="card-image">
                        <img
                            src="<?= APP_BASE_URL ?>/public/assets/images/default.png"
                            alt="<?= htmlspecialchars($card['card_name']) ?>">
                    </div>

                    <div class="card-info">

                        <h4><?= htmlspecialchars($card['card_name']) ?></h4>

                        <p class="price">
                            Foil: <?= htmlspecialchars($card['foil'] === 1 ? 'Yes' : 'No') ?>
                        </p>
                        <p class="price">
                            Physical Condition: <?= htmlspecialchars($card['physical_condition']) ?>
                        </p>

                        <div class="card-actions" onclick="event.stopPropagation()">

                            <a href="<?= APP_BASE_URL ?>/cards/edit/<?= $card['card_id'] ?>">
                                ✏
                            </a>

                            <a href="<?= APP_BASE_URL ?>/cards/delete/<?= $card['card_id'] ?>">
                                🗑
                            </a>

                        </div>

                    </div>

                </div>
            </a>

        <?php endforeach; ?>

    </div>

</div>
