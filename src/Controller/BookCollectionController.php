<?php

namespace App\Controller;

use App\Entity\BookCollection;
use App\Form\BookCollectionType;
use App\Repository\BookCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/book/collection')]
#[IsGranted('ROLE_ADMIN')]
final class BookCollectionController extends AbstractController
{
    #[Route(name: 'app_book_collection_index', methods: ['GET'])]
    public function index(BookCollectionRepository $bookCollectionRepository): Response
    {
        return $this->render('book_collection/index.html.twig', [
            'book_collections' => $bookCollectionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_collection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bookCollection = new BookCollection();
        $form = $this->createForm(BookCollectionType::class, $bookCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bookCollection);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_collection/new.html.twig', [
            'book_collection' => $bookCollection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_collection_show', methods: ['GET'])]
    public function show(BookCollection $bookCollection): Response
    {
        return $this->render('book_collection/show.html.twig', [
            'book_collection' => $bookCollection,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_collection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BookCollection $bookCollection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookCollectionType::class, $bookCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_collection/edit.html.twig', [
            'book_collection' => $bookCollection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_collection_delete', methods: ['POST'])]
    public function delete(Request $request, BookCollection $bookCollection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookCollection->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bookCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_collection_index', [], Response::HTTP_SEE_OTHER);
    }
}
