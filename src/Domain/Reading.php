<?php

namespace App\Domain;

class Reading
{
    private string $client;
    private string $period;
    private float $value;
    private float $median;

    public function __construct(string $client, string $period, float $value, float $median)
    {
        $this->client = $client;
        $this->period = $period;
        $this->value = $value;
        $this->median = $median;
    }

    public function getClient(): string
    {
        return $this->client;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getMedian(): float
    {
        return $this->median;
    }

    /**
     * Determines if the reading is suspicious based on the deviation from the median.
     * A reading is considered suspicious if it deviates more than 50% from the median.
     * @return bool True if the reading is suspicious, false otherwise.
     */
    public function isSuspicious(): bool
    {
        return abs($this->value - $this->median) > ($this->median * 0.5); // Define 50% deviation threshold.
    }
}
