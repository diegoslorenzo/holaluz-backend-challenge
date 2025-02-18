<?php

namespace Tests\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Domain\Reading;

class DetectSuspiciousReadingsControllerTest extends WebTestCase
{
    public function testDetectSuspiciousReadingsWithValidJson()
    {
        $client = static::createClient();
        
        $payload = [
            'readings' => [
                ['clientID' => 'A', 'period' => '2016-01', 'reading' => 100],
                ['clientID' => 'A', 'period' => '2016-02', 'reading' => 5000],
                ['clientID' => 'B', 'period' => '2016-01', 'reading' => 210], // Not suspicious
                ['clientID' => 'B', 'period' => '2016-02', 'reading' => 200], // Not suspicious
            ]
        ];

        $client->request(
            'POST',
            '/detect-suspicious-readings',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        // Create the expected Reading instances
        $expectedReadings = [
            new Reading('A', '2016-01', 100, 2550),
            new Reading('A', '2016-02', 5000, 2550),
        ];

        // Convert the JSON response into Reading objects for comparison
        $actualReadings = array_map(
            fn($reading) => new Reading(
                $reading['client'],
                $reading['period'],
                $reading['reading'],
                $reading['median']
            ),
            $data
        );

        $this->assertEquals($expectedReadings, $actualReadings);
    }

    public function testReturns400ForInvalidJson()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/detect-suspicious-readings',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['invalidKey' => 'wrongValue'])
        );

        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }
}
