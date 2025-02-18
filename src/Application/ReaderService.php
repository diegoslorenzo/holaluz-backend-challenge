<?php

namespace App\Application;

use App\Domain\SuspiciousReadingsDetector;

class ReaderService
{
    private iterable $readers;

    private SuspiciousReadingsDetector $detector;

    public function __construct(iterable $readers, SuspiciousReadingsDetector $detector) // services.yaml will inject the readers here automatically for us to use them in the detectSuspiciousReadings method
    {
        $this->readers = $readers;
        $this->detector = $detector;
    }

    // Factory: Alternative approach using a factory to retrieve readers
    // public function __construct(iterable $readers)
    // {
    //     $this->readers = $readers;
    //     $this->detector = new SuspiciousReadingsDetector();
    // }

    public function detectSuspiciousReadings(string $filePath): array
    {
        foreach ($this->readers as $reader) {
            if ($reader->supports($filePath)) {
                $data = $reader->read($filePath);

                $suspiciousReadings = $this->detector->detect($data);
                $formattedReadings = [];
                return array_map(fn($reading) => [
                    'client' => $reading->getClient(),
                    'period' => $reading->getPeriod(),
                    'reading' => $reading->getValue(),
                    'median' => $reading->getMedian(),
                ], $suspiciousReadings);

                return $formattedReadings;
            }
        }

        throw new \Exception("No suitable reader found for file: " . $filePath);
    }

}
