<?php

use App\Helpers\ViewHelper;

require_once __DIR__ . '/../common/mainHeader.php';


$title = $data['title'] ?? "Cards";
$blueprints = $data["blueprints"] ?? [];
$cards = $data["cards"] ?? [];

ViewHelper::loadHeader($title);
?>
<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/cards.css">
<?php ViewHelper::loadJsScripts(); ?>

<!-- BLUEPRINT SECTION -->

<div class="main-content">
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
        <div class="cards-section">
            <?= App\Helpers\FlashMessage::render() ?>
            <div class="section-header">
                <h2>Cards</h2>

                <a class="add-btn" href="<?= APP_BASE_URL ?>/cards/create">
                    <i class="bi bi-plus-circle"></i> Add Card
                </a>
            </div>

            <!-- SEARCH FORM -->
            <div class="search-form mb-4">
                <form method="GET" action="<?= APP_BASE_URL ?>/cards" class="row g-3">
                    <div class="col-md-2">
                        <input type="text" name="name" class="form-control" placeholder="Card Name" value="<?= hs($data['filters']['name'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="tcg" class="form-control">
                            <option value="">TCG</option>
                            <?php foreach ($data['tcgs'] ?? [] as $tcg): ?>
                                <option value="<?= hs($tcg['tcg_name']) ?>" <?= ($data['filters']['tcg'] ?? '') === $tcg['tcg_name'] ? 'selected' : '' ?>>
                                    <?= hs($tcg['tcg_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="set" class="form-control">
                            <option value="">Set</option>
                            <?php foreach ($data['sets'] ?? [] as $set): ?>
                                <option value="<?= hs($set['set_name']) ?>" <?= ($data['filters']['set'] ?? '') === $set['set_name'] ? 'selected' : '' ?>>
                                    <?= hs($set['set_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="condition" class="form-control">
                            <option value="">Condition</option>
                            <?php foreach ($data['conditions'] ?? [] as $condition): ?>
                                <option value="<?= hs($condition['condition_name']) ?>" <?= ($data['filters']['condition'] ?? '') === $condition['condition_name'] ? 'selected' : '' ?>>
                                    <?= hs($condition['condition_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <select name="foil" class="form-control">
                            <option value="">Foil</option>
                            <option value="1" <?= ($data['filters']['foil'] ?? '') === '1' ? 'selected' : '' ?>>Yes</option>
                            <option value="0" <?= ($data['filters']['foil'] ?? '') === '0' ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12">
                        <a href="<?= APP_BASE_URL ?>/cards" class="btn btn-secondary">Clear Filters</a>
                    </div>
                </div>
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
