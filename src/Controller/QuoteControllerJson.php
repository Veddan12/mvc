<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller that provides a random quote as a JSON response.
 */
class QuoteControllerJson
{
    /**
     * Returns a JSON response containing a random quote with date and timestamp.
     *
     * @return Response A JSON response.
     */
    #[Route("/api/quote")]
    public function jsonQuote(): Response
    {
        $quotes = [
            "An old silent pond - A frog jumps into the pond - Splash! Silence again.",
            "A world of dew - And within every dewdrop - A world of struggle.",
            "The apparition of these faces in the crowd - Petals on a wet, black bough.",
            "I write, erase, rewrite - Erase again, and then - A poppy blooms."
        ];

        // Choose a random quote from the list
        $randomQuote = $quotes[array_rand($quotes)];

        $data = [
            'quote' => $randomQuote,
            'date' => date('Y-m-d'),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Create a JSON response with the quote, today's date, and timestamp
        $response = new JsonResponse($data);

        // Optionally, set JSON_PRETTY_PRINT flag for pretty formatting
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
