<?php

namespace App\Infrastructure\Reader;

use App\Application\ReaderInterface;

class XMLReader implements ReaderInterface
{
    /**
     * Checks if the given file is an XML file.
     *
     * @param string $filename The name of the file.
     * @return bool True if the file extension is "xml", false otherwise.
     */
    public function supports(string $filename): bool
    {
        return pathinfo($filename, PATHINFO_EXTENSION) === 'xml';
    }
    
    /**
     * Reads and parses an XML file into an associative array.
     *
     * @param string $filePath The path to the XML file.
     * @return array The parsed data from the XML file.
     */
    public function read(string $filePath): array
    {
        $data = [];
        $xml = simplexml_load_file($filePath);
        foreach ($xml->reading as $reading) {
            $data[] = [
                'client' => (string) $reading['clientID'],
                'period' => (string) $reading['period'],
                'reading' => (int) $reading
            ];
        }

        return $data;
    }
}
