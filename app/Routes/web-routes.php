<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Controllers\HomeController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\AuthController;
use App\Controllers\CardsController;
use App\Controllers\EmployeeController;
use App\Controllers\DashboardController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminAuthMiddleware;
use App\Controllers\ProfileController;

return static function (Slim\App $app): void {
    $container = $app->getContainer();

    //* NOTE: Route naming pattern: [controller_name].[method_name]
    $app->get('/', [AuthController::class, 'login'])
        ->setName('auth.login');

    $app->get('/home', [HomeController::class, 'index'])
        ->setName('home.index');

    $app->get('/register', [AuthController::class, 'register'])
        ->setName('auth.register');

    $app->post('/register', [AuthController::class, 'store']);

    $app->get('/login', [AuthController::class, 'login'])
        ->setName('auth.login');

    $app->post('/login', [AuthController::class, 'authenticate']);

    $app->get('/logout', [AuthController::class, 'logout'])
        ->setName('auth.logout');
    // A route to display PHP configuration information.
    $app->get('/phpinfo', function (Request $request, Response $response, $args) {
        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();
        $response->getBody()->write($phpinfo);
        return $response;
    });

    // A route to test runtime error handling and custom exceptions.
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpBadRequestException($request, "This is a runtime error. Something went wrong");
    });

    $app->get('/cards', [CardsController::class, 'index'])
        ->setName('card.index')
        ->add($container->get(AuthMiddleware::class));

    $app->get('/cards/blueprint/create', [CardsController::class, 'addBlueprint'])
        ->setName('blueprint.create');

    $app->post('/cards/blueprint/store', [CardsController::class, 'storeBlueprint'])
        ->setName('blueprint.store');

    $app->get('/cards/create', [CardsController::class, 'addCards'])
        ->setName('card.create');

    $app->post('/cards/store', [CardsController::class, 'storeCards'])
        ->setName('card.store');

    $app->get('/cards/{id}/edit-blueprint', [CardsController::class, 'editCardBlueprint'])
        ->setName('blueprint.edit');

    $app->post('/cards/{id}/update-blueprint', [CardsController::class, 'updateCardBlueprint'])
        ->setName('blueprint.update');

    $app->get('/cards/{id}/edit', [CardsController::class, 'editCard'])
        ->setName('card.edit');

    $app->post('/cards/{id}/update', [CardsController::class, 'updateCard'])
        ->setName('card.update');

    $app->get('/cards/{id}/delete', [CardsController::class, 'deleteCard'])
        ->setName('card.delete');

    $app->get('/cards/{id}/delete-blueprint', [CardsController::class, 'deleteCardBlueprint'])
        ->setName('blueprint.delete');

    $app->get('/api/cards/search', [CardsController::class, 'searchCards'])
        ->setName('api.cards.search');

    $app->get('/dashboard', [DashboardController::class, 'index'])
        ->setName('dashboard.index')
        ->add($container->get(AuthMiddleware::class));

    $app->get('/cards/{id}', [CardsController::class, 'showCard'])
        ->setName('card.show')
        ->add($container->get(AuthMiddleware::class));


    $app->get('/profile', [ProfileController::class, 'employeeProfile'])
        ->setName('profile.employee')
        ->add($container->get(AuthMiddleware::class));

    $app->post('/profile/update', [ProfileController::class, 'updateEmployee'])
        ->setName('profile.employee.update')
        ->add($container->get(AuthMiddleware::class));

    $app->get('/admin/profile', [ProfileController::class, 'adminProfile'])
        ->setName('profile.admin')
        ->add($container->get(AdminAuthMiddleware::class))
        ->add($container->get(AuthMiddleware::class));

    $app->post('/admin/profile/update', [ProfileController::class, 'updateAdmin'])
        ->setName('profile.admin.update')
        ->add($container->get(AdminAuthMiddleware::class))
        ->add($container->get(AuthMiddleware::class));
    //! for debugging only
    $app->get('/employees', [EmployeeController::class, 'index'])
        ->setName('admin.employees');

    $app->get('/employees/{id}/edit', [EmployeeController::class, 'edit'])
        ->setName('admin.employees.edit');

    $app->get('/employees/create', [EmployeeController::class, 'showCreate'])
        ->setName('admin.employees.show_create');
    //! Admin routes TEST
    $app->get('/admin/login', [AuthController::class, 'adminLogin'])
        ->setName('auth.adminLogin');

    $app->post('/admin/login', [AuthController::class, 'authenticateAdmin']);

    // Protected admin route example (requires AdminAuthMiddleware)
    $app->get('/admin/dashboard', [DashboardController::class, 'index'])
        ->setName('admin.dashboard')
        ->add(AdminAuthMiddleware::class);

    // Netflix-style profile selection
    $app->get('/profiles', [ProfileController::class, 'selectProfile'])
        ->setName('profile.select')
        ->add($container->get(AuthMiddleware::class));

    // Individual profile page
    $app->get('/profile/{id}', [ProfileController::class, 'viewProfile'])
        ->setName('profile.view')
        ->add($container->get(AuthMiddleware::class));

    // List all employees
    $app->group('/admin', function ($group) {

        $group->get('/employees', [EmployeeController::class, 'index'])
            ->setName('admin.employees');

        $group->get('/employees/create', [EmployeeController::class, 'showCreate'])
            ->setName('admin.employees.show_create');

        $group->post('/employees/create', [EmployeeController::class, 'create'])
            ->setName('admin.employees.create');

        $group->get('/employees/{id}/edit', [EmployeeController::class, 'edit'])
            ->setName('admin.employees.edit');

        $group->post('/employees/{id}/update', [EmployeeController::class, 'update'])
            ->setName('admin.employees.update');

        $group->post('/employees/{id}/delete', [EmployeeController::class, 'delete'])
            ->setName('admin.employees.delete');
    })
        ->add($container->get(AdminAuthMiddleware::class))
        ->add($container->get(AuthMiddleware::class));
};
