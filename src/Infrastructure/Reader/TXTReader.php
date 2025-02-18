<?php

namespace App\Infrastructure\Reader;

use App\Application\ReaderInterface;

class TXTReader implements ReaderInterface
{
    /**
     * Checks if the given file is a TXT file.
     *
     * @param string $filename The name of the file.
     * @return bool True if the file extension is "txt", false otherwise.
     */
    public function supports(string $filename): bool
    {
        return pathinfo($filename, PATHINFO_EXTENSION) === 'txt';
    }
    
    /**
     * Reads and parses a TXT file into an associative array.
     *
     * Each line is expected to have the format: "client period reading".
     *
     * @param string $filePath The path to the TXT file.
     * @return array The parsed data from the file.
     */
    public function read(string $filePath): array
    {
        $data = [];
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            [$client, $period, $reading] = explode(" ", $line); // Assuming format: "client period reading"
            $data[] = [
                'client' => $client,
                'period' => $period,
                'reading' => (int)$reading,
            ];
        }

        return $data;
    }
}
