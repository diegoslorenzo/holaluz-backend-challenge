<?php

namespace Tests\Infrastructure\Reader;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Reader\XMLReader;

class XMLReaderTest extends TestCase
{
    public function testSupportsReturnsTrueForXmlFile()
    {
        $reader = new XMLReader();
        $this->assertTrue($reader->supports('file.xml'));
    }

    public function testSupportsReturnsFalseForNonXmlFile()
    {
        $reader = new XMLReader();
        $this->assertFalse($reader->supports('file.csv'));
    }

    public function testReadParsesXmlCorrectly()
    {
        $reader = new XMLReader();
        $filePath = __DIR__ . '/sample.xml';

        $xmlContent = "<?xml version=\"1.0\"?>\n<readings>\n <reading clientID='A' period='2016-01'>100</reading>\n<reading clientID='A' period='2016-02'>5000</reading>\n</readings>";

        file_put_contents($filePath, $xmlContent);

        $result = $reader->read($filePath);

        $this->assertCount(2, $result);
        $this->assertSame(['client' => 'A', 'period' => '2016-01', 'reading' => 100], $result[0]);
        $this->assertSame(['client' => 'A', 'period' => '2016-02', 'reading' => 5000], $result[1]);

        unlink($filePath);
    }
}
