<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing the library.
 */
final class LibraryController extends AbstractController
{
    /**
     * Library landing page.
     *
     * @return Response
     */
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    /**
     * Handles adding a new book.
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/library/create', name: 'library_create', methods: ['GET', 'POST'])]
    public function addBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        if ($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();

            $library = new Library();
            $library->setTitle((string) $request->request->get('title'));
            $library->setAuthor((string) $request->request->get('author'));
            $library->setIsbn((string) $request->request->get('isbn'));
            $library->setBookcover((string) $request->request->get('bookcover'));

            $entityManager->persist($library);
            $entityManager->flush();

            return $this->redirectToRoute('view_library');
        }
        return $this->render('library/create.html.twig');
    }

    /**
     * Lists all books in the library.
     *
     * @param LibraryRepository $libraryRepository
     * @return Response
     */
    #[Route('/library/view', name: 'view_library')]
    public function viewLibrary(LibraryRepository $libraryRepository): Response
    {

        $library = $libraryRepository->findAll();

        return $this->render('library/view.html.twig', [
            'library' => $library,
        ]);
    }

    /**
     * Displays a single book by its ID.
     *
     * @param LibraryRepository $libraryRepository
     * @param int $id
     * @return Response
     */
    #[Route('/library/view/{id}', name: 'book_by_id')]
    public function viewBookById(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $this->getBookById($libraryRepository, $id);

        return $this->render('library/book.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * Handles delete of a book by ID.
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param LibraryRepository $libraryRepository
     * @param int $id
     * @return Response
     */
    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['GET', 'POST'])]
    public function deleteBook(
        Request $request,
        ManagerRegistry $doctrine,
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $this->getBookById($libraryRepository, $id);

        if ($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($book);
            $entityManager->flush();

            return $this->redirectToRoute('view_library');
        }
        return $this->render('library/delete.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * Handles update of a book's data.
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param LibraryRepository $libraryRepository
     * @param int $id
     * @return Response
     */
    #[Route('/library/update/{id}', name: 'book_update', methods: ['GET', 'POST'])]
    public function updateBook(
        ManagerRegistry $doctrine,
        LibraryRepository $libraryRepository,
        int $id,
        Request $request
    ): Response {
        $book = $this->getBookById($libraryRepository, $id);

        if ($request->isMethod('POST')) {
            $book->setTitle((string) $request->request->get('title'));
            $book->setAuthor((string) $request->request->get('author'));
            $book->setIsbn((string) $request->request->get('isbn'));
            $book->setBookcover((string) $request->request->get('bookcover'));

            $doctrine->getManager()->flush();

            return $this->redirectToRoute('view_library');
        }

        return $this->render('library/update.html.twig', ['book' => $book]);
    }

    /**
     * Helper function to find a book, 404 if not found.
     *
     * @param LibraryRepository $libraryRepository
     * @param int $id
     * @return Library
     */
    private function getBookById(LibraryRepository $libraryRepository, int $id): Library
    {
        $book = $libraryRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }
        return $book;
    }
}
