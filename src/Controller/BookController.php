<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/book')]
final class BookController extends AbstractController
{
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository, UserRepository $userRepository, Request $request): Response
    {
        $user = $this->getUser();
        
        $books = $bookRepository->findAll();

        $books = $bookRepository->findByUser( $user); // même méthode que celle d'après mais en passant par le repository
        // $books = $user->getBooks(); // on récupère les livres de l'utilisateur connecté
        
        
        return $this->render('book/index.html.twig', [
            'title' => 'Liste de vos livres :', 
            'books' => $books,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/all', name: 'app_books',  methods: ['GET'])]
    public function showAll(BookRepository $bookRepository, UserRepository $userRepository, Request $request): Response
    {
        return $this->render('book/index.html.twig', [
            'title' => 'Liste des livres disponibles :', 
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $book = new Book();

        $userId = $request->get('id');
        
        $form = $this->createForm(BookType::class, $book);
        // $form->setUsers(userId);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'app_book_search', methods: ['GET'])]
    public function search(Request $request, BookRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        $bookName = $request->query->get('bookName');
        $book = $bookRepository->findOneBy(['title'=> $bookName]);
 
        return new JsonResponse([
            'message' => 'Hello laly',
            'book'=> [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
            ],
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }

  
}
