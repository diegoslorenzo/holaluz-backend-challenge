<?php

namespace Tests\Domain;

use App\Domain\SuspiciousReadingsDetector;
use App\Domain\Reading;
use PHPUnit\Framework\TestCase;

class SuspiciousReadingsDetectorTest extends TestCase
{
    public function testDetectsSuspiciousReadings()
    {
        $detector = new SuspiciousReadingsDetector();

        $readings = [
            ['client' => 'A', 'period' => '2016-01', 'reading' => 100],
            ['client' => 'A', 'period' => '2016-02', 'reading' => 5000],
            ['client' => 'A', 'period' => '2016-03', 'reading' => 110]
        ];

        $result = $detector->detect($readings);

        $this->assertCount(1, $result);

        $expected = [
            new Reading('A', '2016-02', 5000, 110.0),
        ];

        $this->assertEquals($expected, $result);
    }
}
