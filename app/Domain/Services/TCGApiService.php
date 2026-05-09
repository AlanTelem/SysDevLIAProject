<?php

namespace App\Domain\Services;

use Generator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use JsonMachine\Items;

class TCGApiService extends BaseService
{
    public function fetchAll():?array
    {
        return [];
    }

    public function scryfallBulkUri():String|false{
        $ch=curl_init();
        $url = 'https://api.scryfall.com/bulk-data/oracle_cards';
        curl_setopt($ch, CURLOPT_URL, $url);
        $headers=[
            'User-Agent: tcg-inventory-database-updater/0.1',
            'Accept: application/json; charset=utf-8',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $bulk_response = curl_exec($ch);
        if($bulk_response===false){
            error_log('cURL Error: '. curl_error($ch));
            curl_close($ch);
            return curl_error($ch);

        } else {
            $jsonResponse = json_decode($bulk_response, true);
            return $download_uri = $jsonResponse['download_uri'];
        }
    }
    public function fetchMTG():String
    {
        $uri = $this->scryfallBulkUri();
        $fp = fopen(APP_BASE_DIR_PATH.PATH_SEPARATOR.'data'.PATH_SEPARATOR.date('Ymd').'scryfall_oracle_cards.json', 'w');
        $ch=curl_init($uri);
        curl_setopt($ch, CURLOPT_USERAGENT, 'tcg-inventory-database-updater/0.1');
            $response = curl_setopt($ch, CURLOPT_URL, $uri);
        return $response;
    }

    public function fetchOnePieceSetJson():string|false
    {
        $url = "https://www.optcgapi.com/api/allSets/";
        return file_get_contents($url);
    }

    public function fetchOnePieceCardsJson():string|false
    {
        $url = "https://www.optcgapi.com/api/allSetCards/";
        return file_get_contents($url);
    }

    public function fetchYugiohSetsJson():string|false
    {
        $endpoint = 'https://db.ygoprodeck.com/api/v7/cardsets.php';
        return file_get_contents($endpoint);
    }

    public function fetchYugiohCardsJson():string|false
    {
        $endpoint = 'https://db.ygoprodeck.com/api/v7/cardinfo.php';
        return file_get_contents($endpoint);
    }
}
