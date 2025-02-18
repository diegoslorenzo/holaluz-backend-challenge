<?php

namespace Tests\Infrastructure\Command;

use App\Infrastructure\Command\DetectSuspiciousReadingsCommand;
use App\Application\ReaderService;
use App\Domain\Reading;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Command\Command;

class DetectSuspiciousReadingsCommandTest extends TestCase
{
    public function testCommandExecutesSuccessfully()
    {
        // Mock of the ReaderService to avoid depending on real files
        $mockReaderService = $this->createMock(ReaderService::class);
        
        // Simulated reading
        $reading = new Reading('A', '2016-02', 5000, 100);
        $formattedReading = [
            ['A', '2016-02', 5000, 100]
        ];

        // Simulated suspicious reading
        $mockReaderService->method('detectSuspiciousReadings')
            ->willReturn($formattedReading);

        // Command instance with the mocked service
        $command = new DetectSuspiciousReadingsCommand($mockReaderService);
        $commandTester = new CommandTester($command);

        // Run the command with a fake file
        $commandTester->execute([
            'file' => 'fakefile.csv', // Name of the file is not important
        ]);
        $output = $commandTester->getDisplay();

        // Check that the command finishes successfully
        $this->assertSame(Command::SUCCESS, $commandTester->getStatusCode());

        $this->assertStringContainsString('Client', $output);
        $this->assertStringContainsString('Month', $output);
        $this->assertStringContainsString('Suspicious', $output);
        $this->assertStringContainsString('Median', $output);
        $this->assertStringContainsString('A', $output);
        $this->assertStringContainsString('2016-02', $output);
        $this->assertStringContainsString('5000', $output);

        
    }
}
