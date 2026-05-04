<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Models\AccountsModel;

class AuthController extends BaseController
{
    public function __construct(Container $container, private AccountsModel $accounts)
    {
        parent::__construct($container);
    }

}
