<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LibraryControllerJson extends AbstractController
{
    #[Route("/api/library/books", name: "api_library_books")]
    public function apiShowLibrary(LibraryRepository $libraryRepository): JsonResponse
    {
        $books = $libraryRepository-> findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/library/book/{isbn}", name: "api_library_isbn")]
    public function apiShowBookIsbn(LibraryRepository $libraryRepository, string $isbn): JsonResponse
    {
        $book = $libraryRepository-> findOneBy(['isbn' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with this isbn '.$isbn
            );
        }

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
