<?php

namespace App\Middleware;

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class AdminAuthMiddleware implements MiddlewareInterface
{
    public function __construct(private ResponseFactoryInterface $responseFactory) {}

    public function process(Request $request, RequestHandler $handler): Response
    {
        $profile = SessionManager::get('profile');

        if (!$profile || $profile['privilege'] != 1) {
            FlashMessage::error('You do not have permission to access this page.');

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $dashboardUrl = $routeParser->urlFor('dashboard.index');

            $response = $this->responseFactory->createResponse(302);
            return $response->withHeader('Location', $dashboardUrl);
        }

        return $handler->handle($request);
    }
}
