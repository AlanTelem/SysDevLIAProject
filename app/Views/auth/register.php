<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title);
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Create Account</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= APP_BASE_URL ?>/register">
                <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">
                            Password must be at least 8 characters long and contain at least one number.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <p>Already have an account? <a href="<?= APP_BASE_URL ?>/login">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
