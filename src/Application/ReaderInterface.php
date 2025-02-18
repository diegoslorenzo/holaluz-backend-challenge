<?php

namespace App\Application;

interface ReaderInterface
{
    public function supports(string $filename): bool;
    
    public function read(string $filePath): array;
}
