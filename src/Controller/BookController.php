<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/library/create', name: 'library_create_get', methods: ['GET']) ]
    public function createBook(): Response
    {

        return $this->render('book/create.html.twig');
    }

    #[Route('/library/create', name: 'library_create_post', methods: ['POST'])]
    public function createBookForm(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $title = $request->request->get('title');
        $isbn = $request->request->get('isbn');
        $author = $request->request->get('author');

        $book = new Book();
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);

        $entityManager->persist($book);

        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/show', name: 'library_show_all')]
    public function showAllBook(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        $data = [
            "books" => $books,
        ];

        return $this->render('book/show.html.twig', $data);
    }

    #[Route('/library/show/{id}', name: 'library_show_by_id')]
    public function showOneBook(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        $data = [
            "book" => $book,
        ];

        return $this->render('book/showone.html.twig', $data);
    }

    #[Route('/library/update/{id}', name: 'library_update_get', methods: ['GET'])]
    public function updateBook(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $data = [
            "book" => $book,
        ];

        return $this->render('book/update.html.twig', $data);
    }


    #[Route('/library/update/{id}', name: 'library_update_post', methods: ['POST'])]
    public function updateBookForm(
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $title = $request->request->get('title');
        $isbn = $request->request->get('isbn');
        $author = $request->request->get('author');

        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);

        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/delete/{id}', name: 'library_delete_by_id', methods: ['POST'])]
    public function deleteBookById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }

}
