<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller that handles Library JSON API routes.
 */
class LibraryControllerJson extends AbstractController
{
     /**
     * API library books returns all books in the library.
     *
     * @param LibraryRepository $libraryRepository to fetch book data.
     * @return JsonResponse The list of books in JSON format.
     */
    #[Route("/api/library/books", name: "api_library_books")]
    public function apiShowLibrary(LibraryRepository $libraryRepository): JsonResponse
    {
        $books = $libraryRepository->findAll();
        return $this->createPrettyJsonResponse($books);
    }

    /**
     * API library book returns a single book by its ISBN.
     * 
     * @param LibraryRepository $libraryRepository to fetch book data.
     * @return JsonResponse
     */
    #[Route("/api/library/book/{isbn}", name: "api_library_isbn")]
    public function apiShowBookIsbn(LibraryRepository $libraryRepository, string $isbn): JsonResponse
    {
        $book = $libraryRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException("No book found with ISBN: $isbn");
        }
        return $this->createPrettyJsonResponse($book);
    }

    /**
     * Helper method to create a JSON response with pretty print formatting.
     *
     * @param mixed $data
     * @return JsonResponse The formatted JSON response.
     */
    private function createPrettyJsonResponse(mixed $data): JsonResponse
    {
        $response = $this->json($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
