<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;
use App\Domain\Services\TCGApiService;
use Exception;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;

class CardsModel extends BaseModel
{
    public function __construct(PDOService $db_service, private TCGApiService $tcgapiService)
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
        $this->getOnePieceSets();
        $this->getYugiohSets();
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

    public function getAllTcgs(): array
    {
        $sql = "
        SELECT
            id AS tcg_id,
            name AS tcg_name
        FROM trading_card_games
        ORDER BY name ASC
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
        // Find condition_id from physical_condition
        $condition = $this->selectOne(
            "SELECT id FROM card_condition WHERE physical_condition = :physical_condition",
            [':physical_condition' => $data['physical_condition']]
        );
        $condition_id = $condition['id'] ?? null;

        // Find blueprint_id from set_name and tcg_name
        $blueprint = $this->selectOne(
            "SELECT cb.id
             FROM card_blueprints cb
             JOIN sets s ON cb.set_id = s.id
             JOIN trading_card_games tcg ON s.tcg_id = tcg.id
             WHERE s.name = :set_name AND tcg.name = :tcg_name
             LIMIT 1",
            [
                ':set_name' => $data['set_name'],
                ':tcg_name' => $data['tcg_name']
            ]
        );
        $blueprint_id = $blueprint['id'] ?? null;

        if (!$condition_id || !$blueprint_id) {
            throw new \Exception('Invalid condition or blueprint');
        }

        return $this->execute(
            "UPDATE cards
         SET blueprint_id = :blueprint_id,
             condition_id = :condition_id,
             foil = :foil
         WHERE id = :id",
            [
                ':id' => $id,
                ':blueprint_id' => $blueprint_id,
                ':condition_id' => $condition_id,
                ':foil' => $data['foil'] === 'Yes' ? 1 : 0
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

    public function totalCards(): int
    {
        return $this->count("SELECT COUNT(*) AS total_cards FROM cards");
    }

    public function totalBlueprintss(): int
    {
        return $this->count("SELECT COUNT(*) AS total_blueprints FROM card_blueprints");
    }

    public function searchCards(array $filters): array
    {
        $sql = "
        SELECT
            c.id AS card_id,
            cb.name AS card_name,
            s.name AS set_name,
            tcg.name AS tcg,
            cc.physical_condition AS condition_name,
            CASE WHEN c.foil = 1 THEN 'Foil' ELSE 'Non-Foil' END AS foil,
            COUNT(c.id) AS quantity
        FROM cards c
        LEFT JOIN card_blueprints cb ON c.blueprint_id = cb.id
        LEFT JOIN sets s ON cb.set_id = s.id
        LEFT JOIN trading_card_games tcg ON s.tcg_id = tcg.id
        LEFT JOIN card_condition cc ON c.condition_id = cc.id
        WHERE 1=1
    ";

        $params = [];

        // NAME filter
        if (!empty($filters['name'])) {
            $sql .= " AND cb.name LIKE CONCAT('%', :name, '%')";
            $params[':name'] = $filters['name'];
        }

        // TCG filter
        if (!empty($filters['tcg'])) {
            $sql .= " AND tcg.name = :tcg";
            $params[':tcg'] = $filters['tcg'];
        }

        // SET filter
        if (!empty($filters['set'])) {
            $sql .= " AND s.name = :set";
            $params[':set'] = $filters['set'];
        }

        // CONDITION filter
        if (!empty($filters['condition'])) {
            $sql .= " AND cc.physical_condition = :condition";
            $params[':condition'] = $filters['condition'];
        }

        // FOIL filter
        if ($filters['foil'] !== '') {
            $sql .= " AND c.foil = :foil";
            $params[':foil'] = ($filters['foil'] === '1') ? 1 : 0;
        }

        // VALUE RANGE filter - removed since no value column exists
        // if (!empty($filters['min_value'])) {
        //     $sql .= " AND cf.stock >= :min_value";
        //     $params[':min_value'] = $filters['min_value'];
        // }

        // if (!empty($filters['max_value'])) {
        //     $sql .= " AND cf.stock <= :max_value";
        //     $params[':max_value'] = $filters['max_value'];
        // }

        // GROUP + ORDER
        $sql .= "
        GROUP BY
            c.blueprint_id,
            c.condition_id,
            c.foil
        ORDER BY cb.name ASC
    ";

        return $this->selectAll($sql, $params);
    }

    public function getRecentCards(): array
    {
        $sql = "
        SELECT
            c.id AS card_id,
            cb.name AS card_name,
            c.foil,
            cc.physical_condition
        FROM cards c
        LEFT JOIN card_blueprints cb ON c.blueprint_id = cb.id
        LEFT JOIN card_condition cc ON c.condition_id = cc.id
        ORDER BY c.id DESC
        LIMIT 5
    ";

        return $this->selectAll($sql);
    }

    public function calculateInventoryValue(): float
    {
        return 0.0; // no value stored in your schema
    }

    public function importMTGSets()
    {
        $fileName = APP_BASE_DIR_PATH . '/data/mtgsets.json';
        $sets = Items::fromFile(
            $fileName,
            ['decoder' => new ExtJsonDecoder(true)]
        );
        $sql = 'INSERT INTO sets (tcg_id, name, maker_designated_id, release_date)
    VALUES (1, :name, :maker_designated_id, :release_date)';
        $this->beginTransaction();
        $count = 0;
        foreach ($sets as $set) {
            if ($set['digital'] === true) {
                continue;
            }
            $this->execute(
                $sql,
                [
                    'name' => $set['name'],
                    'maker_designated_id' => $set['code'],
                    'release_date' => $set['released_at']
                ]
            );
            $count++;
            if ($count % 1000 === 0) {
                $this->commit();
                $this->beginTransaction();
            }
        }
        $this->commit();
    }
    public function populateMTGCardsFromScryfall()
    {
        $mtgCardArray = APP_BASE_DIR_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'mtg.json';
        $items = Items::fromFile(
            $mtgCardArray,
            ['decoder' => new ExtJsonDecoder(true)]
        );

        $setLookup = [];

        $sets = $this->selectAll('SELECT id, maker_designated_id FROM sets WHERE tcg_id = 1');

        foreach ($sets as $set) {
            $setLookup[$set['maker_designated_id']] = $set['id'];
        }
        $sql = "
        INSERT INTO card_blueprints (
            name,
            set_id,
            thumbnail_url,
            large_art_url,
            maker_id
        )
        VALUES (
            :name,
            :set,
            :thumbnail_url,
            :large_art_url,
            :maker_id
        )";
        $this->beginTransaction();
        $count = 0;
        foreach ($items as $card) {
            if (
                ($card['object'] ?? '') !== 'card' ||
                ($card['digital'] ?? false) === true ||
                ($card['lang'] ?? 'en') !== 'en'
            ) {
                continue;
            }

            $setCode = $card['set'] ?? null;

            if (!$setCode || !isset($setLookup[$setCode])) {
                continue;
            }
            $thumbnail = $card['image_uris']['small']
                ?? $card['card_faces'][0]['image_uris']['small']
                ?? null;

            $large = $card['image_uris']['large']
                ?? $card['card_faces'][0]['image_uris']['large']
                ?? null;

            $this->execute(
                $sql,
                [
                    'name' => $card['name'],
                    'set' => $setLookup[$setCode],
                    'thumbnail_url' => $thumbnail,
                    'large_art_url' => $large,
                    'maker_id' => $card['oracle_id']
                ]
            );

            $count++;
            if ($count % 1000 === 0) {
                $this->commit();
                $this->beginTransaction();
            }
        }
        $this->commit();
    }

    public function getOnePieceSets(): int
    {
        $json = $this->tcgapiService->fetchOnePieceSetJson();
        if (!$json) {
            throw new Exception('Failed to get data from api.');
        }
        $sets = json_decode($json, true);

        if (!is_array($sets)) {
            throw new Exception("Invalid API response.");
        }

        $existingSets = $this->selectAll('SELECT maker_designated_id FROM sets WHERE tcg_id = 4');

        $sql = 'INSERT INTO sets (
                tcg_id,
                name,
                maker_designated_id
            )
            VALUES (
                4,
                :name,
                :maker_designated_id
            )';

        $count = 0;

        foreach ($sets as $set) {
            if (in_array($set['set_id'], $existingSets)) {
                continue;
            }
            $count += $this->execute($sql, [
                'name' => $set['set_name'],
                'maker_designated_id' => $set['set_id']
            ]);
        }
        return $count;
    }

    public function populateOnePieceBluePrints(): int
    {
        $json = $this->tcgapiService->fetchOnePieceCardsJson();
        if (!$json) {
            throw new Exception('Failed to get data from api.');
        }
        $cards = json_decode($json, true);

        if (!is_array($cards)) {
            throw new Exception("Invalid API response.");
        }

        $existingCards = $this->selectAll(
            'SELECT maker_id
            FROM card_blueprints
            LEFT JOIN sets ON card_blueprints.set_id = sets.id
            WHERE sets.tcg_id = 7'
        );

        $existingSets = $this->selectAll('SELECT id, maker_designated_id FROM sets WHERE tcg_id = 7');
        $setLookup = [];
        foreach ($existingSets as $set) {
            $setLookup[$set['maker_designated_id']] = $set['id'];
        }

        $sql = 'INSERT INTO card_blueprints (
                maker_id,
                set_id,
                name,
                thumbnail_url,
                large_art_url
            )
            VALUES (
                :maker_id,
                :set_id,
                :name,
                :thumbnail_url,
                :large_art_url
            )';

        $count = 0;
        $commitCounter = 0;
        $this->beginTransaction();
        foreach ($cards as $card) {
            if (in_array($card['card_set_id'], $existingCards)) {
                continue;
            }
            $count += $this->execute($sql, [
                'maker_id' => $card['card_set_id'],
                'set_id' => $setLookup[$card['set_id']],
                'name' => $card['card_name'],
                'thumbnail_url' => $card['card_image'] ?? null,
                'large_art_url' => $card['card_image'] ?? null,
            ]);
            $commitCounter++;
            if ($count % 1000 === 0) {
                $this->commit();
                $this->beginTransaction();
            }
        }
        $this->commit();
        return $count;
    }

    public function getYugiohSets(): int
    {
        $json = $this->tcgapiService->fetchYugiohSetsJson();
        if (!$json) {
            throw new Exception('Failed to get data from api.');
        }
        $sets = json_decode($json, true);

        if (!is_array($sets)) {
            throw new Exception("Invalid API response.");
        }

        $existingSets = $this->selectAll('SELECT maker_designated_id FROM sets WHERE tcg_id = 3');

        $sql = 'INSERT INTO sets (
                tcg_id,
                name,
                maker_designated_id,
                release_date
            )
            VALUES (
                3,
                :name,
                :maker_designated_id,
                :release_date
            )';

        $count = 0;

        foreach ($sets as $set) {
            if (in_array($set['set_code'], $existingSets)) {
                continue;
            }
            $count += $this->execute($sql, [
                'name' => $set['set_name'],
                'maker_designated_id' => $set['set_code'],
                'release_date' => $set['tcg_date'] ?? null
            ]);
        }
        return $count;
    }

    public function populateYugiohBluePrints(): int
    {
        $json = $this->tcgapiService->fetchYugiohCardsJson();
        if (!$json) {
            throw new Exception('Failed to get data from api.');
        }
        $cardsDataObject = json_decode($json, true);
        $cards = $cardsDataObject['data'];
        if (!is_array($cards)) {
            throw new Exception("Invalid API response.");
        }

        $existingCards = $this->selectAll(
            'SELECT maker_id
            FROM card_blueprints
            LEFT JOIN sets ON card_blueprints.set_id = sets.id
            WHERE sets.tcg_id = 3'
        );

        $existingLookup = [];

        foreach ($existingCards as $existingCard) {
            $existingLookup[$existingCard['maker_id']] = true;
        }


        $existingSets = $this->selectAll('SELECT id, maker_designated_id FROM sets WHERE tcg_id = 3');
        $setLookup = [];
        foreach ($existingSets as $set) {
            $setLookup[$set['maker_designated_id']] = $set['id'];
        }

        $sql = 'INSERT INTO card_blueprints (
                maker_id,
                set_id,
                name,
                thumbnail_url,
                large_art_url
            )
            VALUES (
                :maker_id,
                :set_id,
                :name,
                :thumbnail_url,
                :large_art_url
            )';

        $count = 0;
        $commitCounter = 0;
        $this->beginTransaction();
        foreach ($cards as $card) {
            if (empty($card['card_sets'])) {
                continue;
            }
            $thumbnail = $card['card_images'][0]['image_url_small'] ?? null;
            $image = $card['card_images'][0]['image_url'] ?? null;
            foreach ($card['card_sets'] as $cardSet) {
                $printingCode = $cardSet['set_code'];
                $setCode = preg_replace('/-\d+$/', '', $cardSet['set_code']);

                if (!isset($setLookup[$setCode])) {
                    continue;
                }
                if (isset($existingLookup[$printingCode])) {
                    continue;
                }

                $count += $this->execute($sql, [
                    'maker_id' => $printingCode,
                    'set_id' => $setLookup[$setCode],
                    'name' => $card['name'],
                    'thumbnail_url' => $thumbnail,
                    'large_art_url' => $image,
                ]);

                $existingLookup[$printingCode] = true;

                $commitCounter++;

                if ($commitCounter % 1000 === 0) {
                    $this->commit();
                    $this->beginTransaction();
                }
            }
        }
        $this->commit();

        return $count;
    }
}
