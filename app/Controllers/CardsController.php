<?php

namespace App\Controllers;

use App\Domain\Models\CardsModel;
use App\Helpers\FlashMessage;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CardsController extends BaseController
{
    public function __construct(
        Container $container,
        private CardsModel $cardsModel
    ) {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $cards = $this->cardsModel->getAllCards();
        $blueprints = $this->cardsModel->getAllCardBlueprints();
        $sets = $this->cardsModel->getAllSets();

        $data = [
            'title' => 'Cards',
            'cards' => $cards,
            'blueprints' => $blueprints,
            'sets' => $sets
        ];

        return $this->render($response, 'cards/cards.IndexView.php', $data);
    }

    public function addBlueprint(Request $request, Response $response, array $args): Response
    {

        $sets = $this->cardsModel->getAllSets();

        $data = [
            'title' => 'Add Card Blueprints',
            'sets' => $sets
        ];

        return $this->render($response, 'cards/blueprint.CreateView.php', $data);
    }

    public function storeBlueprint(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $set_id = $data['set_id'];
        $name = $data['name'];

        if (empty($set_id)) {
            FlashMessage::error('Please select a set');
            return $this->redirect($request, $response, 'blueprint.create');
        }

        if (empty($name)) {
            FlashMessage::error('Please put a valid blueprint name');
            return $this->redirect($request, $response, 'blueprint.create');
        }

        $this->cardsModel->addAndGetBlueprint($data);

        FlashMessage::success('Card Blueprint has been stored successfully');

        return $this->redirect($request, $response, 'card.index');
    }

    public function addCards(Request $request, Response $response, array $args): Response
    {
        $blueprints = $this->cardsModel->getAllCardBlueprints();
        $conditions = $this->cardsModel->getAllCardConditions();

        $data = [
            'title' => 'Add Cards',
            'blueprints' => $blueprints,
            'conditions' => $conditions
        ];

        return $this->render($response, 'cards/cards.CreateView.php', $data);
    }

    public function storeCards(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $blueprint_id = $data['blueprint_id'];
        $condition_id = $data['condition_id'];
        $foil = $data['foil'];

        if (empty($blueprint_id)) {
            FlashMessage::error('Please select a blueprint');
            return $this->redirect($request, $response, 'card.create');
        }

        if (empty($condition_id)) {
            FlashMessage::error('Please select a card condition');
            return $this->redirect($request, $response, 'card.create');
        }

        if ($foil === '') {
            FlashMessage::error('Please put a valid foil');
            return $this->redirect($request, $response, 'card.create');
        }

        $this->cardsModel->addAndGetCard($data);

        FlashMessage::success('Card has been stored successfully');

        return $this->redirect($request, $response, 'card.index');
    }
}
