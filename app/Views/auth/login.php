<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title ?? 'Log In');
?>
<?= App\Helpers\FlashMessage::render() ?>

<div class="container-fluid">
    <div class="row">

        <!-- LEFT SIDE -->
        <div class="col-md-3 left-panel d-none d-md-block">
            <h2>OTHER WORLD GAMES</h2>
            <p>CARDS & GAMES</p>
            <p><strong>Card inventory made simple.</strong></p>

            <div class="left-box"></div>
            <div class="left-box"></div>
            <div class="left-box"></div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-md-9 login-wrapper">
            <div class="login-box">

                <h2 class="login-title">Welcome back!</h2>

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


                    <div class="d-grid">
                        <button type="submit" class="btn btn-login">LOGIN</button>
                    </div>

                </form>

                <a href="#" class="admin-link">Admin Login</a>

            </div>
        </div>

    </div>
</div>

<?php ViewHelper::loadFooter(); ?>