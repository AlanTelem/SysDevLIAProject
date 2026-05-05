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
        ON c.condition_id = cc.id;";

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
}
