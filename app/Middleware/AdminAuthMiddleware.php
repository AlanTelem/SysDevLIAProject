<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class AdminAuthMiddleware
{
    public function __invoke(ServerRequestInterface $request, $handler): ResponseInterface
    {
        if (!isset($_SESSION['profile']) || $_SESSION['profile']['privilege'] != 1) {
            $response = new Response();
            return $response
                ->withHeader('Location', '/dashboard')
                ->withStatus(302);
        }

        return $handler->handle($request);
    }
}
