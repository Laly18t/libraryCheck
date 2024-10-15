<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BooksController extends AbstractController
{
    #[Route('/books', name: 'app_books')]
    public function index( BookRepository $bookRepository, UserRepository $userRepository): Response
    {   
        $user = $this->getUser();
        
        $books = $bookRepository->findAll();

        $books = $bookRepository->findByUser( $user); // même méthode que celle d'après mais en passant par le repository
        // $books = $user->getBooks(); // on récupère les livres de l'utilisateur connecté
        
        

        return $this->render('books/allBooks.html.twig', [
            'controller_name' => 'BooksController',
            'books' => $books
        ]);
    }

    #[Route('/books/user/{id}', name: 'app_books_user')]
    public function showForUser(UserRepository $userRepository, Request $request): Response
    {   
        $userId = $request->get('id');
        $otherUser = $userRepository->findOneBy(['id' => $userId]);

        // $books = $bookRepository->findByUser($otherUser);
        $books = $otherUser->getBooks();
        
        return $this->render('books/allBooks.html.twig', [
            'controller_name' => 'BooksController',
            'books' => $books
        ]);
    }


    #[Route('/books/{id}', name:'book_show')]
    public function showBook(Book $book): Response
    {   
        
        // $book = $userRepository->findByUser();

        return $this->render('books/show.html.twig', [
            'book' => $book
        ]);
    }
}
