<?php

namespace App\Infrastructure\Reader\Factory;

use App\Application\ReaderInterface;
use App\Infrastructure\Reader\CSVReader;
use App\Infrastructure\Reader\XMLReader;
use App\Infrastructure\Reader\TXTReader;
use App\Infrastructure\Reader\JSONReader;

class ReaderFactory
{
    /**
     * Returns all available file readers.
     *
     * This factory method provides an array of reader instances that implement 
     * the `ReaderInterface`. New reader types can be easily added here.
     *
     * @return ReaderInterface[] List of available file readers.
     */
    public static function getReaders(): array
    {
        return [
            new CSVReader(),
            new XMLReader(),
            new TXTReader(),
            new JSONReader(),
        ];
    }
}
