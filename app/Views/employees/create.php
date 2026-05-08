<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title ?? 'Create Employee');
require_once __DIR__ . '/../common/mainHeader.php';
?>

<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/editEmployee.css">

<div class="edit-page">

    <div class="edit-container">

        <!-- LEFT SIDE -->
        <div class="edit-left">

            <div class="image-box">
                <img src="<?= APP_BASE_URL ?>/public/assets/images/user.png"
                    alt="Employee">
            </div>

            <button type="button" class="photo-btn">
                Icon Picture
            </button>

        </div>

        <!-- RIGHT SIDE -->
        <div class="edit-right">

            <h1>CREATE ACCOUNT</h1>

            <form method="POST"
                action="<?= APP_BASE_URL ?>/admin/employees/store">

                <!-- NAME -->
                <div class="form-group">
                    <label>Name:</label>

                    <input type="text"
                        name="name"
                        placeholder="Enter name"
                        required>
                </div>

                <div class="form-group">
                    <label>Username:</label>

                    <input type="text"
                        name="username"
                        placeholder="Enter username"
                        required>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label>Email:</label>

                    <input type="email"
                        name="email"
                        placeholder="Enter email"
                        required>
                </div>

                <!-- PASSWORD -->
                <div class="form-group">
                    <label>Password:</label>

                    <input type="password"
                        name="password"
                        placeholder="Enter password"
                        required>
                </div>

                <!-- POSITION -->
                <div class="form-group position-group">

                    <label>Position:</label>

                    <div class="radio-group">

                        <label class="radio-label">
                            <input type="radio"
                                name="privilege"
                                value="0"
                                checked>

                            Employee
                        </label>

                        <label class="radio-label">
                            <input type="radio"
                                name="privilege"
                                value="1">

                            Admin
                        </label>

                    </div>

                </div>

                <!-- BUTTON -->
                <div class="submit-container">

                    <button type="submit" class="modify-btn">
                        CREATE
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php ViewHelper::loadFooter(); ?>
