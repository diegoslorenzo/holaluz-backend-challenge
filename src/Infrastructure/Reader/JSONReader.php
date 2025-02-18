<?php

namespace App\Infrastructure\Reader;

use App\Application\ReaderInterface;

class JSONReader implements ReaderInterface
{
    /**
     * Checks if the input data is a JSON string.
     *
     * Since JSON data might not come from a file, we use a special identifier "json://"
     * to determine if the input should be handled as JSON.
     *
     * @param string $filename The identifier for JSON input.
     * @return bool True if the identifier starts with "json://".
     */
    public function supports(string $filename): bool
    {
        // If it isn't a file, we could use an identifier like "json://data"
        return str_starts_with($filename, 'json://') || (is_file($filename) && pathinfo($filename, PATHINFO_EXTENSION) === 'json');
    }

    private function readFromString(string $jsonString): string
    {
        // Remove the "json://" prefix to extract the actual JSON content
        $jsonString = str_replace('json://', '', $jsonString);

        return $jsonString;
    }

    private function readFromFile(string $filePath): string
    {
        $jsonString = file_get_contents($filePath);
        if ($jsonString === false) {
            throw new \RuntimeException("Failed to read JSON file: $filePath");
        }

        return $jsonString;
    }

    /**
     * Parses a JSON string into an associative array.
     *
     * @param string $jsonString The JSON string prefixed with "json://".
     * @return array The decoded data.
     * @throws \InvalidArgumentException If the JSON format is invalid.
     */
    public function read(string $input): array
    {
        // Check if the input is a file path or a JSON string
        if (str_starts_with($input, 'json://')) {
            $jsonString = $this->readFromString($input);
        } elseif (is_file($input)) {
            $jsonString = $this->readFromFile($input);
        } else {
            throw new \InvalidArgumentException("Invalid input: Expected a JSON string or a valid JSON file path.");
        }

        // Verifica si el JSON está vacío
        if (empty($jsonString)) {
            throw new \InvalidArgumentException("Invalid JSON: Input is empty.");
        }

        // Decode the JSON data
        $data = json_decode($jsonString, true);

        if (!isset($data['readings']) || !is_array($data['readings'])) {
            throw new \InvalidArgumentException("Invalid JSON format: 'readings' key missing or not an array.");
        }

        $readings_data = [];
        foreach ($data['readings'] as $reading) {
            if (!isset($reading['clientID'], $reading['period'], $reading['reading'])) {
                throw new \InvalidArgumentException("Invalid JSON format: 'client', 'period', or 'reading' key missing in reading.");
            }
            $readings_data[] = [
                'client' => $reading['clientID'],
                'period' => $reading['period'],
                'reading' => $reading['reading']
            ];
        }

        return $readings_data;
    }
}
