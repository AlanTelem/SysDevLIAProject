<?php

namespace App\Controllers;

use App\Domain\Models\EmployeeModel;
use App\Helpers\FlashMessage;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmployeeController extends BaseController
{
    public function __construct(
        Container $container,
        private EmployeeModel $employeeModel
    ) {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response): Response
    {
        $employees = $this->employeeModel->getAllEmployees();

        return $this->render($response, 'employees/employeeList.php', [
            'employees' => $employees
        ]);
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $name = $data['name'];
        $privilege = $data['privilege']; // 0 = employee, 1 = admin

        $this->employeeModel->createEmployee($email, $password, $name, $privilege);

        FlashMessage::success('Employee created successfully');
        return $this->redirect($request, $response, 'employees.index');
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $employee = $this->employeeModel->getEmployeeById($id);

        return $this->render($response, 'employees/edit.php', [
            'employee' => $employee
        ]);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $data = $request->getParsedBody();

        $this->employeeModel->updateEmployee($id, $data);

        FlashMessage::success('Employee updated successfully');
        return $this->redirect($request, $response, 'employees.index');
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $this->employeeModel->deleteEmployee($id);

        FlashMessage::success('Employee deleted successfully');
        return $this->redirect($request, $response, 'employees.index');
    }
}
