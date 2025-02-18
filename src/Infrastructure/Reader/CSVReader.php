<?php

namespace App\Infrastructure\Reader;

use App\Application\ReaderInterface;

class CSVReader implements ReaderInterface
{
    /**
     * Checks if the given file is a CSV file.
     *
     * @param string $filename The name of the file.
     * @return bool True if the file extension is "csv", false otherwise.
     */
    public function supports(string $filename): bool
    {
        return pathinfo($filename, PATHINFO_EXTENSION) === 'csv';
    }
    
    /**
     * Reads and parses a CSV file into an associative array.
     *
     * @param string $filePath The path to the CSV file.
     * @return array The parsed data from the file.
     */
    public function read(string $filePath): array
    {
        $data = [];
        if (($handle = fopen($filePath, "r")) !== false) {
            fgetcsv($handle, 0, ",", '"', "\\"); // skip header
            while (($row = fgetcsv($handle, 0, ",", '"', "\\")) !== false) {
                $data[] = ['client' => $row[0], 'period' => $row[1], 'reading' => (int) $row[2]];
            }
            fclose($handle);
        }
        return $data;
    }
}
