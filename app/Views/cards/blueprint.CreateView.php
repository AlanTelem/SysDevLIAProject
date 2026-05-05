<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? 'Add Card Blueprint';
$sets = $data['sets'] ?? [];

ViewHelper::loadHeader($title);
?>

<h1 class="text-center my-4"><?= hs($title) ?></h1>
<form method="POST" action="<?= APP_BASE_URL ?>/cards/blueprint/store" class="row g-3 m-3">
    <div class="col mb-3">
        <label for="name" class="form-label">Card Blueprint Name</label>
        <input type="text" class="form-control" id="name" name="name"> <!-- required -->
    </div>

    <!-- TODO: Add a select dropdown for category (required) -->
    <!-- Use ViewHelper::renderSelectOptions($categories, '', 'id', 'name') -->
    <!-- to generate the <option> elements -->
    <div class="col-md-4 mb-3">
        <label for="set_id" class="form-label">Set</label>
        <select class="form-select" id="set_id" name="set_id"> <!-- required -->
            <option></option>
            <?= ViewHelper::renderSelectOptions($sets, '', 'set_id', 'set_name');
            ?>
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