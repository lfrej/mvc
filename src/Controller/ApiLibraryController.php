<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiLibraryController extends AbstractController
{
    #[Route("api/library/books", name: "api_library", methods: ['GET'])]
    public function library(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        $test = [];
        foreach ($books as $book) {
            $test[] = [
                'Title' => $book->getTitle(),
                'ISBN' => $book->getIsbn(),
                'Author' => $book->getAuthor(),
            ];
        };

        $data = [
            "Books" => $test,
        ];

        return new JsonResponse($data);
    }

    #[Route("api/library/books/{isbn}", name: "api_library_isbn", methods: ['GET'])]
    public function libraryIsbn(
        ManagerRegistry $doctrine,
        string $isbn
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for isbn '.$isbn
            );
        }

        $data = [
            'Title' => $book->getTitle(),
            'ISBN' => $book->getIsbn(),
            'Author' => $book->getAuthor(),
        ];

        return new JsonResponse($data);
    }
}
