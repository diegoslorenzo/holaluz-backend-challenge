<?php

namespace App\Domain;

class SuspiciousReadingsDetector
{
    /**
     * Detects suspicious readings based on statistical deviation from the median.
     * A reading is considered suspicious if it deviates more than 50% from the median.
     *
     * @param array $readings List of readings containing 'client', 'period', and 'reading' values.
     * @return Reading[] List of suspicious readings with client ID, period, reading value, and median.
     */
    public function detect(array $readings): array
    {
        $clients = [];

        // Group readings by client
        foreach ($readings as $entry) {
            $clients[$entry['client']][] = $entry;
        }

        $suspiciousReadings = [];
        foreach ($clients as $clientId => $clientReadings) {
            $values = array_column($clientReadings, 'reading');
            $median = $this->calculateMedian($values);

            // Identify readings that exceed the threshold
            foreach ($clientReadings as $entry) {
                $reading = new Reading($clientId, $entry['period'], $entry['reading'], $median);
                if ($reading->isSuspicious()) {
                    $suspiciousReadings[] = $reading;
                }
            }
        }

        return $suspiciousReadings;
    }

    /**
     * Calculates the median value of an array of numbers.
     *
     * @param array $values List of numeric values.
     * @return float The median value.
     */
    private function calculateMedian(array $values): float
    {
        sort($values);
        $count = count($values);
        $middle = floor(($count - 1) / 2); // Integer division for better readability
        return ($count % 2) 
            ? $values[$middle] // Odd count: return the middle element
            : ($values[$middle] + $values[$middle + 1]) / 2; // Even count: average two middle elements
    }
}
