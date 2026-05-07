<?php

namespace App\Controllers;

use App\Domain\Models\CardsModel;
use App\Domain\Models\EmployeeModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DashboardController extends BaseController
{
    public function __construct(
        Container $container,
        private CardsModel $cardModel,
        private EmployeeModel $employeeModel
    ) {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response): Response
    {
        // Get logged-in user profile (you already have this in your auth system)
        $profile = $_SESSION['profile'];
        // privilege: 0 = employee, 1 = admin

        // Dashboard data
        $totalCards = $this->cardModel->totalCards();
        $totalEmployees = $this->employeeModel->countEmployees();
        $totalValue = $this->cardModel->calculateInventoryValue();
        $recentCards = $this->cardModel->getRecentCards();

        $data = [
            'totalCards' => $totalCards,
            'totalEmployees' => $totalEmployees,
            'totalValue' => $totalValue,
            'recentCards' => $recentCards
        ];

        // Admin dashboard
        if ($profile['privilege'] == 1) {
            return $this->render($response, 'dashboard/admin.php', $data);
        }

        // Employee dashboard
        return $this->render($response, 'dashboard/employee.php', $data);
    }
}
