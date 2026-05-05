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

return static function (Slim\App $app): void {


    //* NOTE: Route naming pattern: [controller_name].[method_name]
    $app->get('/', [HomeController::class, 'index'])
        ->setName('home.index');

    $app->get('/home', [HomeController::class, 'index'])
        ->setName('home.index');

    $app->get('/register', [AuthController::class, 'register'])
        ->setName('auth.register');

    $app->post('/register', [AuthController::class, 'store']);

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
        ->setName('card.index');

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
};
