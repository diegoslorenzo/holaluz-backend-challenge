<?php

namespace Tests\Infrastructure\Reader;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Reader\TXTReader;

class TXTReaderTest extends TestCase
{
    public function testSupportsReturnsTrueForTxtFile()
    {
        $reader = new TXTReader();
        $this->assertTrue($reader->supports('file.txt'));
    }

    public function testSupportsReturnsFalseForNonTxtFile()
    {
        $reader = new TXTReader();
        $this->assertFalse($reader->supports('file.csv'));
    }

    public function testReadParsesTxtCorrectly()
    {
        $reader = new TXTReader();
        $filePath = __DIR__ . '/sample.txt';

        file_put_contents($filePath, "A 2016-01 100\nA 2016-02 5000");

        $result = $reader->read($filePath);

        $this->assertCount(2, $result);
        $this->assertSame(['client' => 'A', 'period' => '2016-01', 'reading' => 100], $result[0]);
        $this->assertSame(['client' => 'A', 'period' => '2016-02', 'reading' => 5000], $result[1]);

        unlink($filePath);
    }
}