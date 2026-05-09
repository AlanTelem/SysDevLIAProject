<?php

namespace App\Controllers;

use App\Domain\Models\CardsModel;
use App\Domain\Models\ProfileModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReportController extends BaseController
{
    public function __construct(
        Container $container,
        private CardsModel $cardModel
    ) {
        parent::__construct($container);
    }

    public function inventoryReport(Request $request, Response $response): Response
    {
        $cards = $this->cardModel->getAllCards();

        return $this->render($response, 'reports/inventory.php', [
            'cards' => $cards
        ]);
    }
}
