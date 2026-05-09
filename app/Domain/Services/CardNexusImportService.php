<?php

namespace App\Domain\Services;

use App\Domain\Models\CardsModel;
use DI\Container;


class CardNexusImportService extends BaseService
{

    public function __construct(private CardsModel $cardsModel) {
    }


    public function import(string $pathToCSV =  APP_BASE_DIR_PATH.'\public\assets\sample.csv'): int
    {
        $handle = fopen($pathToCSV, 'r');

        if (!$handle) {
            throw new \Exception("Could not open CSV file.");
        }
        $header = fgetcsv($handle, null,',',escape:"\r");

        $cardNexusCsvGameToTCGId = [
            'OnePiece Card Game' => 7,
            'Magic: The Gathering' => 1,
            'Pokémon TCG' => 2,
            'Yu-Gi-Oh!' => 3,
            'Lorcana' => 4,
            'UniVersus' => 5,
            'Flesh and Blood' => 6
        ];

        $existingSets = $this->cardsModel->getAllSets(); //columns are set_id, set_name, tcg_name
        $existingBlueprints = $this->cardsModel->getAllCardBlueprints();
        $conditions = $this->cardsModel->getAllCardConditions();
        $existingCards = $this->cardsModel->getAllCards();

        $setCache = [];
        $blueprintCache = [];
        $conditionMap = [
    'mint' => 1,
    'near mint' => 2,
    'lightly played' => 3,
    'damaged' => 4
];

        foreach ($existingSets as $set) {

            $key =
                strtolower(trim($set['tcg_name']))
                . '|'
                . strtolower(trim($set['set_name']));

            $setCache[$key] = $set['set_id'];
        }

        foreach ($existingBlueprints as $blueprint) {

            $key =
                trim($blueprint['cb.maker_id'])
                . '|'
                . $blueprint['set_id'];

            $blueprintCache[$key] = $blueprint['blueprint_id'];
        }

        foreach ($conditions as $condition) {

            $key = strtolower(trim($condition['physical_condition']));

            $conditionCache[$key] = $condition['id'];
        }

        $cardsToInsert = [];

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            if (!$data) {
                continue;
            }

            $tcgName = trim($data['game']);
            $setName = trim($data['expansion']);
            $cardName = trim($data['name']);
            $makerId = trim($data['printNumber']);
            $conditionName = trim($data['condition']);

            $foil = strtolower(trim($data['finish']) === 'foil') ? 1 : 0;
            $qty = (int)$data['totalQtyOwned'];

            if (!isset($cardNexusCsvGameToTCGId[$tcgName])) {
                continue;
            }

            $tcgId = $cardNexusCsvGameToTCGId[$tcgName];

            $setKey =
                strtolower($tcgName)
                . '|'
                . strtolower($setName);

            if (isset($setCache[$setKey])) {

                $setId = $setCache[$setKey];
            } else {

                $setId = $this->cardsModel->addAndGetSet([
                    'tcg_id' => $tcgId,
                    'name' => $setName
                ]);

                $setCache[$setKey] = $setId;
            }

            $conditionKey = strtolower(trim($conditionName));

if (!isset($conditionMap[$conditionKey])) {
    continue;
}

$conditionId = $conditionMap[$conditionKey];

             $blueprintKey =
            $makerId
            . '|'
            . $setId;

        if (isset($blueprintCache[$blueprintKey])) {

            $blueprintId = $blueprintCache[$blueprintKey];

        } else {

            $blueprintId = $this->cardsModel->addAndGetBlueprint([
                'maker_id' => $makerId,
                'set_id' => $setId,
                'name' => $cardName
            ]);

            $blueprintCache[$blueprintKey] = $blueprintId;
        }
        for ($i = 0; $i < $qty; $i++) {

            $cardsToInsert[] = [
                'blueprint_id' => $blueprintId,
                'condition_id' => $conditionId,
                'foil' => $foil
            ];
        }
        }
        fclose($handle);
        return $this->cardsModel->bulkAddCards($cardsToInsert);
    }
}
