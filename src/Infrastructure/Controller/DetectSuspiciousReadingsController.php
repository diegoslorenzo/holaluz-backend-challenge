<?php

namespace App\Infrastructure\Controller;

use App\Application\ReaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DetectSuspiciousReadingsController extends AbstractController
{
    private ReaderService $readerService;

    /**
     * Constructor to inject the ReaderService dependency.
     *
     * @param ReaderService $readerService Service responsible for detecting suspicious readings.
     */
    public function __construct(ReaderService $readerService)
    {
        $this->readerService = $readerService;
    }

    /**
     * Handles incoming JSON requests to detect suspicious readings.
     *
     * This method acts as an __invoke() controller, allowing it to be directly mapped to a route.
     *
     * @param Request $request The HTTP request containing the readings data in JSON format.
     * @return JsonResponse JSON response containing suspicious readings or an error message.
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Retrieve JSON content from the request body
        $jsonData = $request->getContent();
        
        try {
            // Process the readings and detect anomalies
            $suspiciousReadings = $this->readerService->detectSuspiciousReadings("json://$jsonData");
        } catch (\Exception $e) {
            // Return an error response in case of failure
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        // Return the detected suspicious readings as a JSON response
        return new JsonResponse($suspiciousReadings);
    }
}

