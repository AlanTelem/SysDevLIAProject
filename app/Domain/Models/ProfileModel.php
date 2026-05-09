<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class ProfileModel extends BaseModel
{
    public function __construct(PDOService $db_service)
    {
        parent::__construct($db_service);
    }

    public function getProfile(int $profileId): array
    {
        $sql = "
            SELECT
                p.id AS profile_id,
                p.name,
                p.privilege,
                a.email,
                a.id AS account_id
            FROM profiles p
            JOIN accounts a ON p.account_id = a.id
            WHERE p.id = :id
        ";

        return $this->selectOne($sql, [':id' => $profileId]);
    }

    public function getProfileByAccountId(int $accountId): ?array
    {
        $sql = "
            SELECT
                p.id AS profile_id,
                p.name,
                p.privilege,
                a.email,
                a.id AS account_id
            FROM profiles p
            JOIN accounts a ON p.account_id = a.id
            WHERE a.id = :account_id
        ";

        $result = $this->selectOne($sql, [':account_id' => $accountId]);
        return $result ?: null;
    }

    public function getProfilesByAccountId(int $accountId): array
    {
        $sql = "
            SELECT
                p.id AS profile_id,
                p.name,
                p.privilege,
                a.email,
                a.id AS account_id
            FROM profiles p
            JOIN accounts a ON p.account_id = a.id
            WHERE a.id = :account_id
        ";

        return $this->selectAll($sql, [':account_id' => $accountId]);
    }

    public function updateProfile(int $profileId, array $data)
    {
        // Update name
        $this->execute(
            "UPDATE profiles SET name = :name WHERE id = :id",
            [':name' => $data['name'], ':id' => $profileId]
        );

        // Update email
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
    }
}
