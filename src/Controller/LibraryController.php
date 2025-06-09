<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'library_create', methods: ['GET'])]
    public function createBookForm(
    ): Response {

        return $this->render('library/create.html.twig');
    }

    #[Route('/library/create', name: 'library_create_post', methods: ['POST'])]
    public function addBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();

        $title = ((string) $request->request->get('title'));
        $isbn = ((string) $request->request->get('isbn'));
        $author = ((string) $request->request->get('author'));
        $bookcover = ((string) $request->request->get('bookcover'));

        $library = new Library();
        $library->setTitle($title);
        $library->setAuthor($author);
        $library->setIsbn($isbn);
        $library->setBookcover($bookcover);

        $entityManager->persist($library);
        $entityManager->flush();

        return $this->redirectToRoute('app_library');
    }

    #[Route('/library/view', name: 'view_library')]
    public function viewLibrary(LibraryRepository $libraryRepository): Response
    {

        $library = $libraryRepository->findAll();

        return $this->render('library/view.html.twig', [
            'library' => $library,
        ]);
    }

    #[Route('/library/view/{id}', name: 'book_by_id')]
    public function viewBookById(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        return $this->render('library/book.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['GET'])]
    public function deleteConfirm(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' .$id);
        }

        return $this->render('library/delete.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete_by_id', methods: ['POST'])]
    public function deleteProductById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id '.$id);
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('view_library');
    }

    #[Route('/library/update/{id}', name: 'book_update', methods: ['GET'])]
    public function updateBookForm(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }

        return $this->render('library/update.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/update/{id}', name: 'book_update_by_id', methods: ['POST'])]
    public function updateBook(
        ManagerRegistry $doctrine,
        Request $request,
        int $id,
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id '.$id);
        }

        $book->setTitle((string) $request->request->get('title'));
        $book->setAuthor((string) $request->request->get('author'));
        $book->setIsbn((string) $request->request->get('isbn'));
        $book->setBookcover((string) $request->request->get('bookcover'));

        $entityManager->flush();

        return $this->redirectToRoute('view_library');
    }
}
