<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class EmployeeModel extends BaseModel
{
    public function __construct(PDOService $db_service)
    {
        parent::__construct($db_service);
    }

    public function countEmployees(): int
    {
        $sql = "
        SELECT COUNT(*) AS total
        FROM profiles
        WHERE privilege = 0
    ";

        $row = $this->selectOne($sql);

        return (int) $row['total'];
    }


    public function getAllEmployees(): array
    {
        $sql = "
        SELECT
            p.id AS profile_id,
            a.email,
            p.name,
            p.privilege
        FROM profiles p
        JOIN accounts a ON p.account_id = a.id
        ORDER BY p.name ASC
    ";

        return $this->selectAll($sql);
    }

    public function createEmployee(string $email, string $password, string $name, int $privilege)
    {
        // Insert into accounts
        $this->execute(
            "INSERT INTO accounts (email, hash)
         VALUES (:email, :hash)",
            [
                ':email' => $email,
                ':hash'  => $password
            ]
        );

        // Get the inserted account ID
        $accountId = $this->lastInsertId();

        // Insert into profiles
        $this->execute(
            "INSERT INTO profiles (account_id, name, privilege)
         VALUES (:account_id, :name, :privilege)",
            [
                ':account_id' => $accountId,
                ':name'       => $name,
                ':privilege'  => $privilege
            ]
        );
    }


    public function getEmployeeById(int $id): array|false
    {
        $sql = "
        SELECT
            p.id AS profile_id,
            a.id AS account_id,
            a.email,
            p.name,
            p.privilege
        FROM profiles p
        JOIN accounts a ON p.account_id = a.id
        WHERE p.id = :id
    ";

        return $this->selectOne($sql, [':id' => $id]);
    }

    public function updateEmployee(int $id, array $data)
    {
        // Update account
        $this->execute(
            "UPDATE accounts SET email = :email WHERE id = :account_id",
            [
                ':email' => $data['email'],
                ':account_id' => $data['account_id']
            ]
        );

        // Update password if provided
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $this->execute(
                "UPDATE accounts SET hash = :hash WHERE id = :account_id",
                [
                    ':hash' => $hash,
                    ':account_id' => $data['account_id']
                ]
            );
        }

        // Update profile
        $this->execute(
            "UPDATE profiles
         SET name = :name, privilege = :privilege
         WHERE id = :profile_id",
            [
                ':name' => $data['name'],
                ':privilege' => $data['privilege'],
                ':profile_id' => $id
            ]
        );
    }

    public function deleteEmployee(int $id)
    {
        // Get account_id first
        $profile = $this->selectOne(
            "SELECT account_id FROM profiles WHERE id = :id",
            [':id' => $id]
        );

        $accountId = $profile['account_id'];

        // Delete profile
        $this->execute("DELETE FROM profiles WHERE id = :id", [':id' => $id]);

        // Delete account
        $this->execute("DELETE FROM accounts WHERE id = :id", [':id' => $accountId]);
    }
}
