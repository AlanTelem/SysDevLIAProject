<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader('Select Profile');


$profiles = $data['profiles'] ?? [];
//dd($profiles);

?>

<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/login.css">

<div class="login-page">
    <div class="container-fluid">
        <div class="row">
            <!-- LEFT SIDE -->
            <div class="col-md-3 left-panel d-none d-md-block">
                <img src="/sys-dev-lia/public/assets/images/logo.png"
                    alt="Other World Games Logo"
                    class="logo">
                <p><strong>Select your profile to continue.</strong></p>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-md-9 login-wrapper">
                <div class="login-box" style="max-width: 800px;">
                    <h2 class="login-title">Choose Your Profile</h2>

                    <?php if (empty($profiles)): ?>
                        <p>No profiles found. Please contact an administrator.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($profiles as $profile): ?>
                                <div class="col-md-6 mb-3">
                                    <a href="<?= APP_BASE_URL ?>/profile/<?= $profile['profile_id'] ?>" class="btn btn-login w-100">
                                        <?= htmlspecialchars($profile['name']) ?> (<?= htmlspecialchars($profile['privilege']) ?>)
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="spacer"></div>
                    <a href="<?= APP_BASE_URL ?>/logout" class="admin-link">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
