<?php


namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    // ==================== AFFICHER LA LISTE ====================
    #[Route('/book/list', name: 'app_book_list')]
    public function listBooks(BookRepository $repository): Response
    {
        
        $books = $repository->findBy(['published' => true]);

        
        $publishedCount = count($repository->findBy(['published' => true]));
        $unpublishedCount = count($repository->findBy(['published' => false]));

        return $this->render('book/list.html.twig', [
            'books' => $books,
            'publishedCount' => $publishedCount,
            'unpublishedCount' => $unpublishedCount
        ]);
    }

    // ==================== AJOUTER UN LIVRE ====================
    #[Route('/book/add', name: 'app_book_add')]
    public function addBook(Request $request, ManagerRegistry $doctrine): Response
    {
        $book = new Book();

        $book->setPublished(true);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            $author = $book->getAuthor();

           
            if ($author) {
                $currentNbBooks = $author->getNbBooks() ?? 0;
                $author->setNbBooks($currentNbBooks + 1);
            }

            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Livre ajouté avec succès!');
            return $this->redirectToRoute('app_book_list');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // ==================== MODIFIER UN LIVRE ====================
    #[Route('/book/edit/{ref}', name: 'app_book_edit')]
    public function editBook(int $ref, Request $request, ManagerRegistry $doctrine, BookRepository $repository): Response
    {
        $book = $repository->find($ref);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();

            $this->addFlash('success', 'Livre modifié avec succès!');
            return $this->redirectToRoute('app_book_list');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book
        ]);
    }

    // ==================== SUPPRIMER UN LIVRE ====================
    #[Route('/book/delete/{ref}', name: 'app_book_delete')]
    public function deleteBook(int $ref, ManagerRegistry $doctrine, BookRepository $repository): Response
    {
        $book = $repository->find($ref);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        $em = $doctrine->getManager();

        
    
        $author = $book->getAuthor();

        
        if ($author) {
            $currentNbBooks = $author->getNbBooks() ?? 0;
            $author->setNbBooks(max(0, $currentNbBooks - 1)); 
        }

        $em->remove($book);
        $em->flush();

        $this->addFlash('success', 'Livre supprimé avec succès!');
        return $this->redirectToRoute('app_book_list');
    }

    // ==================== AFFICHER LES DÉTAILS ====================
    #[Route('/book/show/{ref}', name: 'app_book_show')]
    public function showBook(int $ref, BookRepository $repository): Response
    {
        $book = $repository->find($ref);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        return $this->render('book/show.html.twig', [
            'book' => $book
        ]);
    }


   #[Route('/book/count/romance', name: 'app_books_count_romance')]
public function countRomance(BookRepository $bookRepository): Response
{
    $count = $bookRepository->countRomanceBooks();

    return $this->render('book/count.html.twig', [
        'count' => $count,
    ]);
}

}