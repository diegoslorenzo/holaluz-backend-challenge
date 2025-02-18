<?php

namespace Tests\Infrastructure\Reader;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Reader\JSONReader;

class JSONReaderTest extends TestCase
{
    public function testSupportsReturnsTrueForJsonPrefix()
    {
        $reader = new JSONReader();
        $this->assertTrue($reader->supports('json://{"readings":[]}'));
    }

    public function testSupportsReturnsFalseForNonJson()
    {
        $reader = new JSONReader();
        $this->assertFalse($reader->supports('file.csv'));
    }

    public function testReadParsesJsonCorrectly()
    {
        $reader = new JSONReader();
        $json = 'json://{"readings":[{"clientID":"A","period":"2016-01","reading":100}]}';

        $result = $reader->read($json);

        $this->assertCount(1, $result);
        $this->assertSame(['client' => 'A', 'period' => '2016-01', 'reading' => 100], $result[0]);
    }

    public function testReadThrowsExceptionForInvalidJson()
    {
        $reader = new JSONReader();
        $this->expectException(\InvalidArgumentException::class);
        $reader->read('json://{"invalid":true}');
    }
}
