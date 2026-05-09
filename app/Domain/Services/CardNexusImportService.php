<?php

namespace App\Domain\Services;

class CardNexusImportService extends BaseService
{
    public function import($pathToCSV)
    {
        $handle = fopen($pathToCSV, 'r');

        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
        }
    }
}
