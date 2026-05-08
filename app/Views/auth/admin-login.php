<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title ?? 'Admin Login');
?>
<?= App\Helpers\FlashMessage::render() ?>
<div class="login-page">
    <div class="container-fluid">
        <div class="row">

            <!-- LEFT SIDE -->
            <div class="col-md-3 left-panel d-none d-md-block">
                <img src="/SysDevLIAProject/public/assets/images/logo.png"
                    alt="Other World Games Logo"
                    class="logo">
                <p><strong>Card inventory made simple.</strong></p>

                <div class="left-box"></div>
                <div class="left-box"></div>
                <div class="left-box"></div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-md-9 login-wrapper">

                <div class="login-box">

                    <h1 class="login-title">Admin Login</h1>


                    <form method="POST" action="<?= APP_BASE_URL ?>/login">

                        <div class="mb-3">
                            <label for="identifier" class="form-label">Username</label>
                            <input
                                type="text"
                                class="form-control"
                                id="identifier"
                                name="identifier"
                                placeholder="@username"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="password"
                                required>
                        </div>
                        <div class="spacer"></div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-login">LOGIN</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
