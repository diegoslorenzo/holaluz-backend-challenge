<?php

namespace Tests\Domain;

use App\Domain\Reading;
use PHPUnit\Framework\TestCase;

class ReadingTest extends TestCase
{
    public function testReadingCanBeCreatedAndAccessed()
    {
        $reading = new Reading('A123', '2024-01', 200, 150);

        $this->assertSame('A123', $reading->getClient());
        $this->assertSame('2024-01', $reading->getPeriod());
        $this->assertSame(200.0, $reading->getValue());
        $this->assertSame(150.0, $reading->getMedian());
    }

    public function testReadingIsSuspiciousWhenAboveThreshold()
    {
        // 200 is more than 50% above the median 100 → should be suspicious
        $reading = new Reading('B456', '2024-02', 200, 100);
        $this->assertTrue($reading->isSuspicious());
    }

    public function testReadingIsSuspiciousWhenBelowThreshold()
    {
        // 40 is more than 50% below the median 100 → should be suspicious
        $reading = new Reading('C789', '2024-03', 40, 100);
        $this->assertTrue($reading->isSuspicious());
    }

    public function testReadingIsNotSuspiciousWhenWithinThreshold()
    {
        // 140 is within 50% of 100 → should not be suspicious
        $reading = new Reading('D321', '2024-04', 140, 100);
        $this->assertFalse($reading->isSuspicious());

        // 60 is within 50% of 100 → should not be suspicious
        $reading = new Reading('E654', '2024-05', 60, 100);
        $this->assertFalse($reading->isSuspicious());
    }
}
