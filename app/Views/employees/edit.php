<?php

use App\Helpers\ViewHelper;


$employee = $data['employee'] ?? [];
ViewHelper::loadHeader($title ?? 'Modify Employee');
//dd($employee);
?>


<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/editEmployee.css">

<div class="edit-page">

    <div class="edit-container">

        <!-- LEFT SIDE -->
        <div class="edit-left">

            <div class="image-box">
                <img src="<?= $employee['privilege'] == 1
                                ? APP_BASE_URL . '/public/assets/images/admin.png'
                                : APP_BASE_URL . '/public/assets/images/user.png' ?>"
                    alt="Employee">
            </div>

            <button class="photo-btn">
                Icon Picture
            </button>

        </div>

        <!-- RIGHT SIDE -->
        <div class="edit-right">

            <h1>MODIFY USER</h1>

            <form method="POST"
                action="/admin/employees/<?= $employee['profile_id'] ?>/update">

                <!-- ACCOUNT ID -->
                <input type="hidden"
                    name="account_id"
                    value="<?= $employee['account_id'] ?>">

                <!-- USERNAME -->
                <div class="form-group">
                    <label>Username:</label>

                    <input type="text"
                        name="name"
                        value="<?= htmlspecialchars($employee['name']) ?>"
                        required>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label>Email:</label>

                    <input type="email"
                        name="email"
                        value="<?= htmlspecialchars($employee['email']) ?>"
                        required>
                </div>

                <!-- PASSWORD -->
                <div class="form-group">
                    <label>Password:</label>

                    <input type="password"
                        name="password"
                        placeholder="Leave blank to keep current password">
                </div>

                <!-- POSITION -->
                <div class="form-group position-group">

                    <label>Position:</label>

                    <div class="radio-group">

                        <label class="radio-label">
                            <input type="radio"
                                name="privilege"
                                value="0"
                                <?= $employee['privilege'] == 0 ? 'checked' : '' ?>>

                            Employee
                        </label>

                        <label class="radio-label">
                            <input type="radio"
                                name="privilege"
                                value="1"
                                <?= $employee['privilege'] == 1 ? 'checked' : '' ?>>

                            Admin
                        </label>

                    </div>

                </div>


                <!-- BUTTON -->
                <div class="submit-container">

                    <button type="submit" class="modify-btn">
                        MODIFY
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php ViewHelper::loadFooter(); ?>
