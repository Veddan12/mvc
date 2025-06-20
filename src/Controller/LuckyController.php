<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller that provides lucky number and hello page.
 */
class LuckyController
{
    /**
     * Returns a simple HTML response displaying a random lucky number between 0 and 100.
     *
     * @return Response
     */
    #[Route('/lucky/number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

    /**
     * Returns a simple HTML response with hello message.
     *
     * @return Response
     */
    #[Route("/lucky/hello")]
    public function hello(): Response
    {
        return new Response(
            '<html><body>Hello to you!</body></html>'
        );
    }
}
