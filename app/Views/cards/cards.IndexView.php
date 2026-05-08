<?php

use App\Helpers\ViewHelper;

require_once __DIR__ . '/../common/dashboardHeader.php';
require_once __DIR__ . '/../common/dashboardHeader.php';


$title = $data['title'] ?? "Cards";
$blueprints = $data["blueprints"] ?? [];
$cards = $data["cards"] ?? [];

ViewHelper::loadHeader($title);
?>
<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/cards.css">
<?php ViewHelper::loadJsScripts(); ?>

<!-- BLUEPRINT SECTION -->
<?= App\Helpers\FlashMessage::render() ?>
<div class="main-content">
    <?= App\Helpers\FlashMessage::render() ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 m-3">
            <h2>Card Blueprints</h2>

            <a class="btn btn-primary" href="<?= APP_BASE_URL ?>/cards/blueprint/create">
                <i class="bi bi-plus-circle"></i> Add Card Blueprint
            </a>
        </div>


        <div class="table-responsive mb-5">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Set</th>
                        <th>Blueprint Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blueprints as $bp): ?>
                        <tr>
                            <td><?= hs($bp['blueprint_id']) ?></td>
                            <td><?= hs($bp['set_name']) ?></td>
                            <td><?= hs($bp['blueprint_name']) ?></td>
                            <td class="text-center">
                                <a href="<?= APP_BASE_URL ?>/cards/<?= $bp['blueprint_id'] ?>/edit-blueprint" class="btn btn-sm btn-warning me-2">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="<?= APP_BASE_URL ?>/cards/<?= $bp['blueprint_id'] ?>/delete-blueprint" class="btn btn-sm btn-danger"
                                    onclick="event.preventDefault(); confirmDeleteCardBlueprint(<?= hs($bp['blueprint_id']) ?>, '<?= hs($bp['blueprint_name']) ?>')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- CARDS SECTION -->
        <!-- CARDS SECTION -->
        <div class="cards-section">

            <div class="section-header">
                <h2>Cards</h2>

                <a class="add-btn" href="<?= APP_BASE_URL ?>/cards/create">
                    <i class="bi bi-plus-circle"></i> Add Card
                </a>
            </div>

            <div class="cards-grid">

                <?php foreach ($cards as $card): ?>

                    <div class="card-item">

                        <!-- CLICKABLE CARD CONTENT -->
                        <a
                            href="<?= APP_BASE_URL ?>/cards/<?= $card['card_id'] ?>"
                            class="card-link">

                            <!-- IMAGE -->
                            <div class="card-image">

                                <img
                                    src="<?= APP_BASE_URL ?>/public/assets/images/default.png"
                                    alt="<?= hs($card['card_name']) ?>">

                            </div>

                            <!-- INFO -->
                            <div class="card-info">

                                <h3><?= hs($card['card_name']) ?></h3>

                                <p class="condition">
                                    Condition: <?= hs($card['condition_name']) ?>
                                </p>
                                <p class="condition">
                                    ID: <?= hs($card['card_id']) ?>
                                </p>

                                <span class="foil-badge">
                                    Foil: <?= hs($card['foil']) ?>
                                </span>

                            </div>

                        </a>

                        <!-- ACTIONS -->
                        <div class="card-actions">

                            <a
                                href="<?= APP_BASE_URL ?>/cards/<?= $card['card_id'] ?>/edit"
                                class="edit-btn">

                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <a
                                href="<?= APP_BASE_URL ?>/cards/<?= $card['card_id'] ?>/delete"
                                class="delete-btn"
                                onclick="event.preventDefault(); confirmDeleteCard(<?= hs($card['card_id']) ?>, '<?= hs($card['card_name']) ?>')">

                                <i class="bi bi-trash"></i>
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>
    <?php ViewHelper::loadFooter(); ?>
