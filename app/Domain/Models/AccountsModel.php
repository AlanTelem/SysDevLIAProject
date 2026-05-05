<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class AccountsModel extends BaseModel
{
    public function createAccount(array $data): int
    {
        $this->execute(
            'INSERT INTO `accounts` (email, hash) VALUES (:email, :password)',
            [
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT)
            ]
        );
        return intval($this->lastInsertId());
    }

    public function findByEmail(string $email): ?array
    {
        // TODO: Look up a single user by their email address
        //       Return the user data or null if not found
        $sql = 'SELECT * FROM accounts WHERE email = :email';
        $result = $this->selectOne($sql, [':email' => $email]);

        if (is_bool($result)) {
            return null;
        } else {
            return $result;
        }
    }

    public function emailExists(string $email): bool
    {
        return !is_bool($this->selectOne("SELECT 'id' FROM accounts WHERE email = ?", [$email]));
    }

    public function verifyCredentials(string $email, string $password): ?array
    {
        // TODO: Look up the user by their identifier (try email first, then username).
        //       If found, verify the password against the stored hash.
        //       Return the user data on success or null on failure.
        $user_data = $this->findByEmail($email);
        if (is_null($user_data)) {
            return $user_data;
        }
        if (password_verify($password, $user_data['hash']))
            return $user_data;
        else return null;
    }
}
