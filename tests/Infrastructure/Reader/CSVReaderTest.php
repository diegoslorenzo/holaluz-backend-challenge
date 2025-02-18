<?php

namespace Tests\Infrastructure\Reader;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Reader\CSVReader;

class CSVReaderTest extends TestCase
{
    public function testSupportsReturnsTrueForCsvFile()
    {
        $reader = new CSVReader();
        $this->assertTrue($reader->supports('file.csv'));
    }

    public function testSupportsReturnsFalseForNonCsvFile()
    {
        $reader = new CSVReader();
        $this->assertFalse($reader->supports('file.xml'));
    }

    public function testReadParsesCsvCorrectly()
    {
        $reader = new CSVReader();
        $filePath = __DIR__ . '/sample.csv';
        
        file_put_contents($filePath, "client,period,reading\nA,2016-01,100\nA,2016-02,5000");

        $result = $reader->read($filePath);

        $this->assertCount(2, $result);
        $this->assertSame(['client' => 'A', 'period' => '2016-01', 'reading' => 100], $result[0]);
        $this->assertSame(['client' => 'A', 'period' => '2016-02', 'reading' => 5000], $result[1]);

        unlink($filePath);
    }
}
