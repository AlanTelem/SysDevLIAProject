<!-- app/Views/cards/details.php -->

<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title ?? 'Card Details');
require_once __DIR__ . '/../common/mainHeader.php';

$cardV = $card ?? null;

if (!$cardV) {
    echo "<p>Card not found.</p>";
    return;
}
?>

<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/cardDetails.css">

<div class="details-page">

    <!-- HEADER -->
    <div class="details-header">

        <div class="header-left">

            <a href="<?= APP_BASE_URL ?>/cards" class="back-btn">
                ←
            </a>

            <div>
                <h1>Card Details</h1>
                <p>View & Edit card information</p>
            </div>

        </div>

        <div class="header-actions">

            <button type="submit" form="cardForm" class="save-btn">
                💾 Save
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
                action="<?= APP_BASE_URL ?>/cards/update/<?= $cardV['card_id'] ?>"
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
                        <label>Set Name</label>

                        <input
                            type="text"
                            name="set_name"
                            value="<?= htmlspecialchars($cardV['set_name']) ?>">
                    </div>

                    <div class="detail-box">
                        <label>Physical Condition</label>

                        <input
                            type="text"
                            name="physical_condition"
                            value="<?= htmlspecialchars($cardV['physical_condition']) ?>">
                    </div>

                    <div class="detail-box">
                        <label>Trading Card Game</label>

                        <input
                            type="text"
                            name="tcg_name"
                            value="<?= htmlspecialchars($cardV['tcg_name']) ?>">
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