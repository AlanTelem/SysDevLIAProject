<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader('Employee Profile');

require_once __DIR__ . '/../common/mainHeader.php';
?>

<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/profile.css">

<div class="profile-page">

    <!-- PROFILE SECTION -->
    <div class="profile-card">

        <div class="profile-header">
            <h1><?= htmlspecialchars($profile['username'] ?? 'Employee') ?></h1>
            <span class="role-badge employee">Employee</span>
        </div>

        <div class="profile-info">

            <div class="info-box">
                <label>Email</label>
                <p><?= htmlspecialchars($profile['email'] ?? '') ?></p>
            </div>

            <div class="info-box">
                <label>Position</label>
                <p><?= htmlspecialchars($profile['position'] ?? '') ?></p>
            </div>

        </div>

    </div>

    <!-- OPERATIONS SECTION -->
    <div class="operations-card">

        <div class="section-header">
            <h2>My Recent Operations</h2>
        </div>

        <?php if (!empty($operations)): ?>

            <table class="operations-table">

                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Card</th>
                        <th>Brand</th>
                        <th>Quantity</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($operations as $op): ?>

                        <tr>

                            <td><?= htmlspecialchars($op['event']) ?></td>
                            <td><?= htmlspecialchars($op['card_name']) ?></td>
                            <td><?= htmlspecialchars($op['brand']) ?></td>

                            <td class="<?= str_contains($op['quantity'], '+') ? 'positive' : 'negative' ?>">
                                <?= htmlspecialchars($op['quantity']) ?>
                            </td>

                            <td><?= htmlspecialchars($op['date']) ?></td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty-state">
                No operations found.
            </div>

        <?php endif; ?>

    </div>

</div>