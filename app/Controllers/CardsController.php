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

    public function deleteCard(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];

        if ($id <= 0) {
            FlashMessage::error('Invalid card ID');
            return $this->redirect($request, $response, 'card.index');
        }

        $rowsAffected = $this->cardsModel->deleteCard($id);

        if ($rowsAffected > 0) {
            FlashMessage::success('Card has been deleted successfully');
        } else {
            FlashMessage::error('Card not found or could not be deleted');
        }

        return $this->redirect($request, $response, 'card.index');
    }

    public function deleteCardBlueprint(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];

        if ($id <= 0) {
            FlashMessage::error('Invalid blueprint ID');
            return $this->redirect($request, $response, 'card.index');
        }

        try {
            $rowsAffected = $this->cardsModel->deleteCardBlueprint($id);
        } catch (\PDOException $e) {
            // Check if the error is due to foreign key constraint violation
            if ($e->getCode() === '23000') {
                FlashMessage::error('Cannot delete this blueprint because there are cards associated with it. Please delete the associated cards first.');
            } else {
                FlashMessage::error('An error occurred while trying to delete the blueprint. Please try again later.');
            }
            return $this->redirect($request, $response, 'card.index');
        }

        if ($rowsAffected > 0) {
            FlashMessage::success('Card Blueprint has been deleted successfully');
        } else {
            FlashMessage::error('Card Blueprint not found or could not be deleted');
        }

        return $this->redirect($request, $response, 'card.index');
    }

    public function editCardBlueprint(Request $request, Response $response, array $args): Response
    {
        $blueprint_id = $args['id'];

        $blueprint = $this->cardsModel->findByBlueprintId($blueprint_id);

        if (!$blueprint) {
            FlashMessage::error('Card Blueprint not found!');
            $this->redirect($request, $response, 'card.index');
        }

        $sets = $this->cardsModel->getAllSets();

        $data = [
            'title' => 'Edit Card Blueprint',
            'blueprint' => $blueprint,
            'sets' => $sets
        ];

        return $this->render($response, 'cards/blueprint.EditView.php', $data);
    }

    public function updateCardBlueprint(Request $request, Response $response, array $args): Response
    {
        $blueprint_id = $args['id'];

        $data = $request->getParsedBody();

        $set_id = $data['set_id'];
        $name = $data['name'];

        if (empty($set_id)) {
            FlashMessage::error('Please select a set');
            return $this->redirect($request, $response, 'blueprint.edit');
        }

        if (empty($name)) {
            FlashMessage::error('Please put a valid blueprint name');
            return $this->redirect($request, $response, 'blueprint.edit');
        }

        $this->cardsModel->updateCardBlueprint($blueprint_id, $data);

        FlashMessage::success('Card Blueprint has been updated successfully');

        return $this->redirect($request, $response, 'card.index');
    }

    public function editCard(Request $request, Response $response, array $args): Response
    {
        $card_id = $args['id'];

        $card = $this->cardsModel->findByCardId($card_id);

        if (!$card) {
            FlashMessage::error('Card not found!');
            $this->redirect($request, $response, 'card.index');
        }

        $blueprints = $this->cardsModel->getAllCardBlueprints();
        $conditions = $this->cardsModel->getAllCardConditions();

        $data = [
            'title' => 'Edit Cards',
            'card' => $card,
            'blueprints' => $blueprints,
            'conditions' => $conditions
        ];

        return $this->render($response, 'cards/cards.EditView.php', $data);
    }

    public function updateCard(Request $request, Response $response, array $args): Response
    {
        $card_id = $args['id'];

        $data = $request->getParsedBody();

        $blueprint_id = $data['blueprint_id'];
        $condition_id = $data['condition_id'];
        $foil = $data['foil'];

        if (empty($blueprint_id)) {
            FlashMessage::error('Please select a blueprint');
            return $this->redirect($request, $response, 'card.edit');
        }

        if (empty($condition_id)) {
            FlashMessage::error('Please select a card condition');
            return $this->redirect($request, $response, 'card.edit');
        }

        if ($foil === '') {
            FlashMessage::error('Please put a valid foil');
            return $this->redirect($request, $response, 'card.edit');
        }

        $this->cardsModel->updateCard($card_id, $data);

        FlashMessage::success('Card has been updated successfully');

        return $this->redirect($request, $response, 'card.index');
    }

    public function searchCards(Request $request, Response $response, array $args): Response
    {
        $query = $request->getQueryParams();

        // Collect all filters
        $filters = [
            'name'      => trim($query['name'] ?? ''),
            'brand'     => trim($query['brand'] ?? ''),
            'rarity'    => trim($query['rarity'] ?? ''),
            'condition' => trim($query['condition'] ?? ''),
            'foil'      => trim($query['foil'] ?? ''),
            'min_value' => $query['min_value'] ?? null,
            'max_value' => $query['max_value'] ?? null,
        ];

        // Limit name length
        if (strlen($filters['name']) > 100) {
            $filters['name'] = substr($filters['name'], 0, 100);
        }

        // Call model
        $cards = $this->cardsModel->searchCards($filters);

        // Prepare JSON response
        $data = [
            'success' => true,
            'count'   => count($cards),
            'filters' => $filters,
            'cards'   => $cards
        ];

        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function showCard(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];

        $card = $this->cardsModel->getCardById($id);

        if (!$card) {
            return $this->redirect($request, $response, 'card.index');
        }

        return $this->render($response, 'cards/cards.DetailView.php', [
            'card' => $card
        ]);
    }
}
