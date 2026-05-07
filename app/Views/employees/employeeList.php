<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title ?? 'Employee List');
$employees = $data['employees'] ?? [];
?>

<link rel="stylesheet" href="<?= APP_BASE_URL ?>/public/assets/css/employeeList.css">

<div class="employee-page">

    <div class="employee-container">

        <!-- HEADER -->
        <div class="employee-header">
            <h1>List of Employees:</h1>

            <a href="<?= APP_BASE_URL ?>/employees/create" class="create-btn">
                Create Employee
            </a>
        </div>

        <!-- EMPLOYEE LIST -->
        <?php foreach ($employees as $employee): ?>

            <div class="employee-card">

                <!-- PROFILE ICON -->
                <div class="employee-avatar">
                    <img src="<?= $employee['privilege'] == 1
                                    ? 'public/assets/images/admin.png'
                                    : 'public/assets/images/user.png' ?>"
                        alt="User">
                </div>

                <!-- INFO -->
                <div class="employee-info">

                    <!-- TOP ROW -->
                    <div class="employee-top">

                        <div class="info-box name-box">
                            <?= htmlspecialchars($employee['name']) ?>
                        </div>

                        <div class="info-box tasks-box">
                            Tasks Completed:
                            <span>12</span>
                        </div>

                    </div>

                    <!-- BOTTOM ROW -->
                    <div class="employee-bottom">

                        <div class="info-box role-box">
                            <?= $employee['privilege'] == 1 ? 'Admin' : 'Employee' ?>
                        </div>

                        <div class="info-box time-box">
                            Work Time:
                            <span>6h-12h</span>
                        </div>

                    </div>

                </div>

                <!-- ACTION BUTTONS -->
                <div class="employee-actions">

                    <a href="<?= APP_BASE_URL ?>/employees/<?= $employee['profile_id'] ?>/edit"
                        class="modify-btn">
                        Modify
                    </a>

                    <form method="POST"
                        action="<?= APP_BASE_URL ?>/admin/employees/<?= $employee['profile_id'] ?>/delete"
                        onsubmit="return confirm('Delete this employee?');">

                        <button type="submit" class="delete-btn">
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<?php ViewHelper::loadFooter(); ?>
