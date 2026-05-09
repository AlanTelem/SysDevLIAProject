<?php

namespace App\Controllers;

use App\Domain\Models\ProfileModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends BaseController
{
    public function __construct(
        Container $container,
        private ProfileModel $profileModel
    ) {
        parent::__construct($container);
    }

    private function formatOperations(array $ops): array
    {
        return array_map(function ($op) {
            return [
                'event' => $op['action'],              // Stock Added / Stock Removed
                'card_name' => $op['card_name'],       // Blue-Eyes White Dragon
                'brand' => $op['tcg'],                 // Yu-Gi-Oh
                'quantity' => ($op['units'] > 0 ? '+' : '') . $op['units'] . ' Units',
                'user' => $op['user_name'],            // Stanley Parable
                'date' => date('m/d/Y h:i A', strtotime($op['timestamp']))
            ];
        }, $ops);
    }


    public function employeeProfile(Request $request, Response $response): Response
    {
        $profile = SessionManager::get('profile');

        if ($profile === null) {
            FlashMessage::error('Profile not found. Please contact an administrator.');
            return $this->redirect($request, $response, 'card.index');
        }

        $data = $this->profileModel->getProfile($profile['id']);
        //$data['position'] = 'Employee Position';   // ADD THIS
        $data['position'] = ($data['privilege'] == 1)
            ? 'Administrator Position'
            : 'Employee Position';

        // Employee sees ONLY their own operations
        $allOps = $_SESSION['inventory_operations'] ?? [];
        $filtered = array_filter($allOps, fn($op) => $op['user_id'] === $profile['id']);
        $operations = $this->formatOperations($filtered);   // ADD THIS

        return $this->render($response, 'profile/employee.php', [
            'profile' => $data,
            'operations' => $operations
        ]);
    }

    public function selectProfile(Request $request, Response $response): Response
    {
        $account = SessionManager::get('account');

        if (!$account) {
            return $this->redirect($request, $response, 'login');
        }

        $profiles = $this->profileModel->getProfilesByAccountId($account['account_id']);

        return $this->render($response, 'profile/select.php', [
            'profiles' => $profiles
        ]);
    }

    public function adminProfile(Request $request, Response $response): Response
    {
        $profile = SessionManager::get('profile');

        if ($profile === null) {
            FlashMessage::error('Profile not found. Please contact an administrator.');
            return $this->redirect($request, $response, 'card.index');
        }

        $data = $this->profileModel->getProfile($profile['id']);
        //$data['position'] = 'Admin Position';   // ADD THIS
        $data['position'] = ($data['privilege'] == 1)
            ? 'Administrator Position'
            : 'Employee Position';
        // Admin sees ALL operations
        $allOps = $_SESSION['inventory_operations'] ?? [];
        $operations = $this->formatOperations($allOps);   // ADD THIS

        return $this->render($response, 'profile/admin.php', [
            'profile' => $data,
            'operations' => $operations
        ]);
    }



    public function updateEmployee(Request $request, Response $response): Response
    {
        $profile = SessionManager::get('profile');

        if ($profile === null) {
            FlashMessage::error('Profile not found. Please contact an administrator.');
            return $this->redirect($request, $response, 'card.index');
        }

        $data = $request->getParsedBody();

        $this->profileModel->updateProfile($profile['id'], $data);

        return $response->withHeader('Location', '/profile')->withStatus(302);
    }

    public function updateAdmin(Request $request, Response $response): Response
    {
        $profile = SessionManager::get('profile');

        if ($profile === null) {
            FlashMessage::error('Profile not found. Please contact an administrator.');
            return $this->redirect($request, $response, 'card.index');
        }

        $data = $request->getParsedBody();

        $this->profileModel->updateProfile($profile['id'], $data);

        return $response->withHeader('Location', '/admin/profile')->withStatus(302);
    }
    public function viewProfile(Request $request, Response $response, array $args): Response
    {
        $profileId = (int)$args['id'];

        $data = $this->profileModel->getProfile($profileId);

        if (!$data) {
            FlashMessage::error('Profile not found.');
            return $this->redirect($request, $response, 'profile.select');
        }

        // Save selected profile into session
        SessionManager::set('profile', [
            'id' => $data['profile_id'],
            'name' => $data['name'],
            'privilege' => $data['privilege']
        ]);

        // Redirect based on role
        if ($data['privilege'] === '1') {
            return $response
                ->withHeader('Location', APP_BASE_URL . '/dashboard')
                ->withStatus(302);
        }

        return $response
            ->withHeader('Location', APP_BASE_URL . '/profile')
            ->withStatus(302);
    }
}
