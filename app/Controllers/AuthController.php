<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Models\AccountsModel;
use App\Helpers\SessionManager;
use App\Helpers\FlashMessage;
use Exception;

class AuthController extends BaseController
{
    public function __construct(Container $container, private AccountsModel $accounts_model)
    {
        parent::__construct($container);
    }

    public function register(Request $request, Response $response, array $args): Response
    {
        $data['data'] = [
            'title' => 'Register | Create a new account',
        ];

        if (isset($_SESSION["account_info"])) {
            $data['data']['account_info'] = $_SESSION["account_info"];
            unset($_SESSION["account_info"]);
        }
        return $this->render($response, "auth/register.php", $data);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        $form_data = $request->getParsedBody();

        $return_to_form = false;
        if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            FlashMessage::error('Enter a valid email');
            $return_to_form = true;
        }
        if ($this->accounts_model->emailExists($form_data['email'])) {
            FlashMessage::error('This email is already registered');
            $return_to_form = true;
        }
        // regex string to match 8 characters or more
        if (
            !preg_match("/^.{8,}$/i", $form_data['password']) &&
            // regex string to match any digit
            !preg_match("/\d/", $form_data['password'])
        ) {
            FlashMessage::error('Password must be at least 8 characters long and contain a number');
            $return_to_form = true;
        }
        if ($form_data['password'] !== $form_data['confirm_password']) {
            FlashMessage::error('Passwords do not match');
            $return_to_form = true;
        }

        if ($return_to_form) {
            SessionManager::set("account_info", $form_data);
            return $this->redirect($request, $response, 'auth.register');
        }

        $userParams = [
            'email' => $form_data['email'],
            'password' => $form_data['password']
        ];
        try {
            $accountId = $this->accounts_model->createAccount($userParams);
            FlashMessage::success("Created user #$accountId");
            return $this->render($response, '/auth/login.php');
        } catch (Exception $e) {
            FlashMessage::error($e->getMessage());
            return $this->redirect($request, $response, 'auth.register');
        }
    }

    public function login(Request $request, Response $response, array $args): Response
    {
        $data = [
            'title' => 'Login'
        ];
        return $this->render($response, 'auth/login.php', $data);
    }

    public function authenticate(Request $request, Response $response, array $args): Response
    {
        $form_data = $request->getParsedBody();
        if (isset($form_data['identifier']) && isset($form_data['password'])) {
            $account = $this->accounts_model->verifyCredentials($form_data['identifier'], $form_data['password']);
            if ($account === null) {
                FlashMessage::error('Invalid Credentials.');
                return $this->redirect($request, $response, 'auth.login');
            }
            SessionManager::set('account', [
                'account_id' => $account['id'],
                'email' => $account['email'],
                'is_authenticated' => true
            ]);
            FlashMessage::success("You are logged in with  " . SessionManager::get('account')['email']);
            return $this->redirect($request, $response, 'home.index');
        }
        FlashMessage::error('Please input credentials');
        return $this->redirect($request, $response, 'auth.login');
    }

    public function logout(Request $request, Response $response, array $args): Response
    {
        SessionManager::destroy();
        FlashMessage::success('You have successfully logged out');
        return $this->redirect($request, $response, 'auth.login');
    }
}
