<?php

declare(strict_types=1);

namespace src\Service;

use DateTime;
use Exception;
use src\Config\FileConstants;
use src\Model\Transaction;

class CsvReader
{
    /**
     * @throws Exception
     */
    public function read(string $filename): array
    {
        $rows = [];

        if (!file_exists($filename)) {
            throw new Exception("File not found: $filename");
        }

        if (($handle = fopen($filename, 'r')) !== false) {
            while (($data = fgetcsv($handle, FileConstants::BUFFER_SIZE, ',')) !== false) {

                if (count($data) !== FileConstants::COLUMNS_COUNT) {
                    fclose($handle);
                    throw new Exception("Invalid CSV format. Expected 6 columns but found " . count($data));
                }

                $transaction = new Transaction(
                    new DateTime($data[0]),
                    (int)$data[1],
                    $data[2],
                    $data[3],
                    (string)$data[4],
                    $data[5]
                );
                $rows[] = $transaction;
            }
            fclose($handle);
        }
        return $rows;
    }
}
