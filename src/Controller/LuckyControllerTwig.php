<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for rendering pages using Twig templates.
 */
class LuckyControllerTwig extends AbstractController
{
    /**
     * Route that displays a random number.
     *
     * @return Response
     */
    #[Route("/lucky/twig", name: "lucky")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky.html.twig', $data);
    }

    /**
     * Route for the home page.
     *
     * @return Response
     */
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * Route for the about page.
     *
     * @return Response
     */
    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    /**
     * Route for the project report page.
     *
     * @return Response
     */
    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    /**
     * Route for the API.
     *
     * @return Response
     */
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('api.html.twig');
    }

    /**
     * Route for the card game landing page.
     *
     * @return Response
     */
    #[Route("/card", name: "card")]
    public function card(): Response
    {
        return $this->render('card/home.html.twig');
    }

    /**
     * Route for the game 21 landing page.
     *
     * @return Response
     */
    #[Route("/game", name: "game")]
    public function landningpage(): Response
    {
        return $this->render('game/home.html.twig');
    }

    /**
     * Route for the library.
     *
     * @return Response
     */
    #[Route("/library", name: "library")]
    public function bookpage(): Response
    {
        return $this->render('library/index.html.twig');
    }

    /**
     * Route for displaying metrics.
     *
     * @return Response
     */
    #[Route("/metrics", name: "metrics")]
    public function metrics(): Response
    {
        return $this->render('metrics.html.twig');
    }

}
