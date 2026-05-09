<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title ?? 'Edit Card');
require_once __DIR__ . '/../common/mainHeader.php';

$cardV = $card ?? null;

if (!$cardV) {
    echo "<p>Card not found.</p>";
    return;
}
?>

<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/cardDetails.css">

<div class="details-page">

    <?= App\Helpers\FlashMessage::render() ?>

    <!-- HEADER -->
    <div class="details-header">

        <div class="header-left">

            <a href="<?= APP_BASE_URL ?>/cards" class="back-btn">
                ←
            </a>

            <div>
                <h1>Edit Card</h1>
                <p>Edit card information</p>
            </div>

        </div>

        <div class="header-actions">

            <button type="submit" form="cardForm" class="save-btn">
                💾 Save Changes
            </button>

            <a href="<?= APP_BASE_URL ?>/cards" class="cancel-btn">
                ⊗ Cancel
            </a>

        </div>

    </div>

    <!-- MAIN -->
    <div class="details-container">

        <!-- IMAGE -->
        <div class="image-section">

            <img
                src="<?= APP_BASE_URL ?>/public/assets/images/<?= $cardV['card_image'] ?? 'default.png' ?>"
                alt="<?= htmlspecialchars($cardV['card_name']) ?>">

        </div>

        <!-- DETAILS -->
        <div class="form-section">

            <form
                id="cardForm"
                method="POST"
                action="<?= APP_BASE_URL ?>/cards/<?= $cardV['card_id'] ?>/update"
                enctype="multipart/form-data">

                <!-- TOP -->
                <div class="card-top">

                    <div>
                        <h2><?= htmlspecialchars($cardV['card_name']) ?></h2>

                        <p>
                            <?= htmlspecialchars($cardV['tcg_name']) ?>
                        </p>
                    </div>

                    <span class="foil-badge">

                        Foil: <?= htmlspecialchars($cardV['foil']) ?>
                    </span>

                </div>

                <!-- DETAILS GRID -->
                <div class="details-grid">

                    <div class="detail-box">
                        <label>Trading Card Game</label>

                        <select name="tcg_name">
                            <option value="">Select TCG</option>
                            <?php foreach ($tcgs ?? [] as $tcg): ?>
                                <option value="<?= htmlspecialchars($tcg['tcg_name']) ?>" <?= $cardV['tcg_name'] === $tcg['tcg_name'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tcg['tcg_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="detail-box">
                        <label>Set Name</label>

                        <select name="set_name">
                            <option value="">Select Set</option>
                            <?php foreach ($sets ?? [] as $set): ?>
                                <option value="<?= htmlspecialchars($set['set_name']) ?>" <?= $cardV['set_name'] === $set['set_name'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($set['set_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="detail-box">
                        <label>Physical Condition</label>

                        <select name="physical_condition">
                            <option value="">Select Condition</option>
                            <?php foreach ($conditions ?? [] as $condition): ?>
                                <option value="<?= htmlspecialchars($condition['condition_name']) ?>" <?= $cardV['physical_condition'] === $condition['condition_name'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($condition['condition_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="detail-box">
                        <label>Foil</label>

                        <select name="foil">
                            <option value="Yes"
                                <?= $cardV['foil'] === 'Yes' ? 'selected' : '' ?>>
                                Yes
                            </option>

                            <option value="No"
                                <?= $cardV['foil'] === 'No' ? 'selected' : '' ?>>
                                No
                            </option>
                        </select>
                    </div>

                </div>

            </form>

        </div>

    </div>

</div>
