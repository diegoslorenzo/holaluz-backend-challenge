<?php

namespace Tests\Application;

use PHPUnit\Framework\TestCase;
use App\Application\ReaderService;
use App\Application\ReaderInterface;
use App\Infrastructure\Reader\Factory\ReaderFactory;

class ReaderServiceTest extends TestCase
{
    public function testDetectSuspiciousReadingsWithValidReader()
    {
        // Reader mock that supports the file
        $mockReader = $this->createMock(ReaderInterface::class);
        $mockReader->method('supports')->willReturn(true);
        $mockReader->method('read')->willReturn([
            ['client' => 'A', 'period' => '2016-01', 'reading' => 100],
            ['client' => 'A', 'period' => '2016-02', 'reading' => 2000],
            ['client' => 'A', 'period' => '2016-03', 'reading' => 5000] // Ejemplo sospechoso
        ]);

        // Create the service with the mock
        $readerService = new ReaderService([$mockReader], new \App\Domain\SuspiciousReadingsDetector());

        // Execute the detectSuspiciousReadings method
        $result = $readerService->detectSuspiciousReadings('data.csv');

        // Verifies that suspicious readings are detected
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result); // 100 and 5000 are suspicious
    }

    public function testThrowsExceptionWhenNoReaderSupportsFile()
    {
        $mockReader = $this->createMock(ReaderInterface::class);
        $mockReader->method('supports')->willReturn(false);

        $readerService = new ReaderService([$mockReader], new \App\Domain\SuspiciousReadingsDetector());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No suitable reader found');

        $readerService->detectSuspiciousReadings('data.unknown');
    }

}
