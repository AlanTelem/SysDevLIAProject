<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? 'Add Card';
$blueprints = $data['blueprints'] ?? [];
$conditions = $data['conditions'] ?? [];

ViewHelper::loadHeader($title);
?>

<h1 class="text-center my-4"><?= hs($title) ?></h1>
<form method="POST" action="<?= APP_BASE_URL ?>/cards/store" class="row g-3 m-3">
    <!-- TODO: Add a select dropdown for category (required) -->
    <!-- Use ViewHelper::renderSelectOptions($categories, '', 'id', 'name') -->
    <!-- to generate the <option> elements -->
    <div class="col-md-6 mb-3">
        <label for="blueprint_id" class="form-label">Blueprints</label>
        <select class="form-select" id="blueprint_id" name="blueprint_id"> <!-- required -->
            <option></option>
            <?= ViewHelper::renderSelectOptions($blueprints, '', 'blueprint_id', 'blueprint_name');
            ?>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="condition_id" class="form-label">Conditions</label>
        <select class="form-select" id="condition_id" name="condition_id"> <!-- required -->
            <option></option>
            <?= ViewHelper::renderSelectOptions($conditions, '', 'condition_id', 'condition_name') ?>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="foil" class="form-label">Conditions</label>
        <select class="form-select" id="foil" name="foil"> <!-- required -->
            <option value="0">Non‑foil</option>
            <option value="1">Foil</option>
        </select>
    </div>

    <div class="d-flex justify-content-evenly align-items-center">
        <!-- TODO: Add submit button -->
        <button type="submit" class="btn btn-success px-5">Submit</button>

        <!-- TODO: Add cancel link back to the admin product list -->
        <a class="btn btn-danger px-5" href="<?= APP_BASE_URL ?>/cards">Cancel</a>
    </div>

</form>

<?php ViewHelper::loadFooter(); ?>