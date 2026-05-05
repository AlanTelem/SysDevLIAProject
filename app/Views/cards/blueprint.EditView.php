<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? 'Edit Card Blueprint';
$blueprint = $data['blueprint'] ?? [];
$sets = $data['sets'] ?? [];

ViewHelper::loadHeader($title);
?>

<h1 class="text-center my-4"><?= hs($title) ?></h1>

<form method="POST" action="<?= APP_BASE_URL ?>/cards/<?= hs($blueprint['blueprint_id']) ?>/update-blueprint" class="row g-3 m-3">
    <div class="col mb-3">
        <label for="name" class="form-label">Card Blueprint Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= hs($blueprint['blueprint_name']) ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="set_id" class="form-label">Set</label>
        <select class="form-select" id="set_id" name="set_id">
            <option></option>
            <?= ViewHelper::renderSelectOptions($sets, $blueprint['set_id'], 'set_id', 'set_name');
            ?>
        </select>
    </div>

    <div class="d-flex justify-content-evenly align-items-center">
        <!-- TODO: Add submit button -->
        <button type="submit" class="btn btn-success px-5">Update</button>

        <!-- TODO: Add cancel link back to the admin product list -->
        <a class="btn btn-danger px-5" href="<?= APP_BASE_URL ?>/cards">Cancel</a>
    </div>

</form>

<?php ViewHelper::loadFooter(); ?>