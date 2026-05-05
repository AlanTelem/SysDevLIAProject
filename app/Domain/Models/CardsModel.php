<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class CardsModel extends BaseModel
{
    public function __construct(PDOService $db_service)
    {
        parent::__construct($db_service);
    }

    public function getAllCards(): array
    {
        $sql = "
        SELECT
            c.id AS card_id,
            cb.name AS card_name,
            s.name AS set_name,
            tcg.name AS tcg_name,
            cc.physical_condition AS condition_name,
            CASE WHEN c.foil = 1 THEN 'Yes' ELSE 'No' END AS foil
        FROM cards c
        JOIN card_blueprints cb ON c.blueprint_id = cb.id
        JOIN sets s ON cb.set_id = s.id
        JOIN trading_card_games tcg ON s.tcg_id = tcg.id
        JOIN card_condition cc ON c.condition_id = cc.id
        ORDER BY c.id ASC
    ";

        return $this->selectAll($sql);
    }

    public function getCardById(int $id): ?array
    {
        $sql =
            "SELECT
    c.id AS card_id,
    cb.name AS card_name,
    s.name AS set_name,
    tcg.name AS tcg_name,
    cc.physical_condition,
    CASE
        WHEN c.foil = 1 THEN 'Yes'
        ELSE 'No'
    END AS foil
FROM cards c
JOIN card_blueprints cb
    ON c.blueprint_id = cb.id
JOIN sets s
    ON cb.set_id = s.id
JOIN trading_card_games tcg
    ON s.tcg_id = tcg.id
JOIN card_condition cc
    ON c.condition_id = cc.id
WHERE c.id = :id;";

        $params = ['id' => $id];
        return $this->selectOne($sql, $params);
    }

    public function getCardsByTcgId(int $tcg_id): array
    {
        $sql =
            "SELECT
    c.id AS card_id,
    cb.name AS card_name,
    s.name AS set_name,
    tcg.name AS tcg_name,
    cc.physical_condition,
    c.foil
FROM cards c
JOIN card_blueprints cb ON c.blueprint_id = cb.id
JOIN sets s ON cb.set_id = s.id
JOIN trading_card_games tcg ON s.tcg_id = tcg.id
JOIN card_condition cc ON c.condition_id = cc.id
WHERE tcg.id = :tcg_id;";

        $params = ['tcg_id' => $tcg_id];
        return $this->selectAll($sql, $params);
    }

    public function getCardsBySetId(int $set_id): array
    {
        $sql =
            "SELECT
    c.id AS card_id,
    cb.name AS card_name,
    s.name AS set_name,
    tcg.name AS tcg_name,
    cc.physical_condition,
    c.foil
FROM cards c
JOIN card_blueprints cb ON c.blueprint_id = cb.id
JOIN sets s ON cb.set_id = s.id
JOIN trading_card_games tcg ON s.tcg_id = tcg.id
JOIN card_condition cc ON c.condition_id = cc.id
WHERE s.id = :set_id;";

        $params = ['set_id' => $set_id];
        return $this->selectAll($sql, $params);
    }

    function getCardByConditionId(int $condition_id): array
    {
        $sql =
            "SELECT
    c.id AS card_id,
    cb.name AS card_name,
    s.name AS set_name,
    tcg.name AS tcg_name,
    cc.physical_condition,
    c.foil
FROM cards c
JOIN card_blueprints cb ON c.blueprint_id = cb.id
JOIN sets s ON cb.set_id = s.id
JOIN trading_card_games tcg ON s.tcg_id = tcg.id
JOIN card_condition cc ON c.condition_id = cc.id
WHERE cc.id = :condition_id;";

        $params = ['condition_id' => $condition_id];
        return $this->selectAll($sql, $params);
    }

    public function getCardsByFoil(bool $foil): array
    {
        $sql = "SELECT
        c.id AS card_id,
        cb.name AS card_name,
        s.name AS set_name,
        tcg.name AS tcg_name,
        cc.physical_condition,
        c.foil
    FROM cards c
    JOIN card_blueprints cb ON c.blueprint_id = cb.id
    JOIN sets s ON cb.set_id = s.id
    JOIN trading_card_games tcg ON s.tcg_id = tcg.id
    JOIN card_condition cc ON c.condition_id = cc.id
    WHERE c.foil = :foil;";

        $params = ['foil' => $foil ? 1 : 0];
        return $this->selectAll($sql, $params);
    }

    public function getAllSets()
    {
        $sql = "
        SELECT
            s.id AS set_id,
            s.name AS set_name,
            tcg.name AS tcg_name
        FROM sets s
        JOIN trading_card_games tcg ON s.tcg_id = tcg.id
        ORDER BY s.release_date ASC
    ";

        return $this->selectAll($sql);
    }

    public function getAllCardBlueprints()
    {
        $sql = "
        SELECT
            cb.id AS blueprint_id,
            cb.name AS blueprint_name,
            s.id AS set_id,
            s.name AS set_name
        FROM card_blueprints cb
        JOIN sets s ON cb.set_id = s.id
    ";

        return $this->selectAll($sql);
    }

    public function getAllCardConditions()
    {
        $sql = "
        SELECT
            id AS condition_id,
            physical_condition AS condition_name
        FROM card_condition
    ";

        return $this->selectAll($sql);
    }

    public function addAndGetBlueprint(array $data): string
    {
        $this->execute(
            "INSERT INTO card_blueprints (set_id, name)
         VALUES (:set_id, :name)",
            [
                'set_id' => $data['set_id'],
                'name' => $data['name']
            ]
        );

        return $this->lastInsertId();
    }

    public function addAndGetCard(array $data): string
    {
        $this->execute(
            "INSERT INTO cards (blueprint_id, condition_id, foil)
         VALUES (:blueprint_id, :condition_id, :foil)",
            [
                'blueprint_id' => $data['blueprint_id'],
                'condition_id' => $data['condition_id'],
                'foil' => $data['foil']
            ]
        );

        return $this->lastInsertId();
    }

    public function findByBlueprintId(int $id): array|false
    {
        return $this->selectOne(
            "SELECT
            cb.id AS blueprint_id,
            cb.name AS blueprint_name,
            cb.set_id
         FROM card_blueprints cb
         WHERE cb.id = :id
         LIMIT 1",
            [':id' => $id]
        );
    }


    public function updateCardBlueprint(int $id, array $data): int
    {
        return $this->execute(
            "UPDATE card_blueprints
         SET set_id = :set_id,
             name = :name
         WHERE id = :id",
            [
                ':id' => $id,
                ':set_id' => $data['set_id'],
                ':name' => $data['name'],
            ]
        );
    }

    public function findByCardId(int $id): array|false
    {
        return $this->selectOne(
            "SELECT
            c.id AS card_id,
            c.blueprint_id,
            c.condition_id,
            CASE WHEN c.foil = 1 THEN 'Yes' ELSE 'No' END AS foil
         FROM cards c
         WHERE c.id = :id
         LIMIT 1",
            [':id' => $id]
        );
    }


    public function updateCard(int $id, array $data): int
    {
        return $this->execute(
            "UPDATE cards
         SET blueprint_id = :blueprint_id,
             condition_id = :condition_id,
             foil = :foil
         WHERE id = :id",
            [
                ':id' => $id,
                ':blueprint_id' => $data['blueprint_id'],
                ':condition_id' => $data['condition_id'],
                ':foil' => $data['foil']
            ]
        );
    }


    public function deleteCard(int $id): int
    {
        $rowsAffected = $this->execute("DELETE FROM cards WHERE id = :id", ['id' => $id]);

        return $rowsAffected;
    }
    public function deleteCardBlueprint(int $id): int
    {
        $rowsAffected = $this->execute("DELETE FROM card_blueprints WHERE id = :id", ['id' => $id]);

        return $rowsAffected;
    }
}
