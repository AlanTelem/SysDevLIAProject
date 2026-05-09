<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader('Inventory Report');
$cards = $data['cards'] ?? [];
require_once __DIR__ . '/../common/mainHeader.php';
?>

<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/report.css">

<div class="report-page">

    <div class="report-header">

        <h1>Inventory Report</h1>

        <button onclick="window.print()" class="print-btn">
            Print Report
        </button>

    </div>

    <table class="report-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Card</th>
                <th>Set</th>
                <th>TCG</th>
                <th>Condition</th>
                <th>Foil</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($cards as $card): ?>

                <tr>
                    <td><?= htmlspecialchars($card['card_id']) ?></td>
                    <td><?= htmlspecialchars($card['card_name']) ?></td>
                    <td><?= htmlspecialchars($card['set_name']) ?></td>
                    <td><?= htmlspecialchars($card['tcg_name']) ?></td>
                    <td><?= htmlspecialchars($card['condition_name']) ?></td>
                    <td><?= htmlspecialchars($card['foil']) ?></td>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>
