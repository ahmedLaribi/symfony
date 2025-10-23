<?php
// src/Controller/AuthorController.php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Service\HappyQuote;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    // ==================== AFFICHER LA LISTE ====================
    #[Route('/author/list', name: 'app_author_list')]
    public function listAuthors(AuthorRepository $repository): Response
    {
        // Récupérer tous les auteurs
        $authors = $repository->findAll();

        return $this->render('author/list.html.twig', [
            'authors' => $authors
        ]);
    }

    // ==================== AJOUTER UN AUTEUR ====================
    #[Route('/author/add', name: 'app_author_add')]
    public function addAuthor(Request $request, ManagerRegistry $doctrine): Response
    {
        // Créer une nouvelle instance d'Author
        $author = new Author();

        // Initialiser nb_books à 0 par défaut
        $author->setNbBooks(0);

        // Créer le formulaire
        $form = $this->createForm(AuthorType::class, $author);

        // Traiter la requête
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'entity manager
            $em = $doctrine->getManager();

            // Persister l'objet
            $em->persist($author);

            // Exécuter la requête SQL
            $em->flush();

            // Message de confirmation
            $this->addFlash('success', 'Auteur ajouté avec succès!');

            // Rediriger vers la liste
            return $this->redirectToRoute('app_author_list');
        }

        // Afficher le formulaire
        return $this->render('author/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // ==================== MODIFIER UN AUTEUR ====================
    #[Route('/author/edit/{id}', name: 'app_author_edit')]
    public function editAuthor(int $id, Request $request, ManagerRegistry $doctrine, AuthorRepository $repository): Response
    {
        // Récupérer l'auteur à modifier
        $author = $repository->find($id);

        // Vérifier si l'auteur existe
        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        // Créer le formulaire pré-rempli
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Pas besoin de persist() car l'entité existe déjà
            $em = $doctrine->getManager();
            $em->flush();

            $this->addFlash('success', 'Auteur modifié avec succès!');
            return $this->redirectToRoute('app_author_list');
        }

        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author
        ]);
    }

    // ==================== SUPPRIMER UN AUTEUR ====================
    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    public function deleteAuthor(int $id, ManagerRegistry $doctrine, AuthorRepository $repository): Response
    {
        // Récupérer l'auteur à supprimer
        $author = $repository->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        // Supprimer l'auteur
        $em = $doctrine->getManager();
        $em->remove($author);
        $em->flush();

        $this->addFlash('success', 'Auteur supprimé avec succès!');
        return $this->redirectToRoute('app_author_list');
    }

    // ==================== AFFICHER LES DÉTAILS ====================
    #[Route('/author/show/{id}', name: 'app_author_show')]
    public function showAuthor(int $id, AuthorRepository $repository): Response
    {
        $author = $repository->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        return $this->render('author/show.html.twig', [
            'author' => $author
        ]);
    }

     #[Route('/ShowAllAuthorQB',name:'ShowAllAuthorQB')]
    public function ShowAllAuthorQB(AuthorRepository $repo){
       $authors=$repo->showAllQB();
       return $this->render(
            'author/showAll.html.twig'
            ,
            ['list' => $authors]
        );
    }

#[Route('/ShowAllDQL', name : 'showAllDql')]
    public function ShowAllDQL(AuthorRepository $repo)
    {
        $authors=$repo->ShowAllAuthorDQL();
        return $this->render('author/showall.html.twig', ['list' =>$authors]);
    }


    #[Route('/authors', name: 'app_author_list')]
    public function list(ManagerRegistry $doctrine, HappyQuote $happyQuote): Response
    {
        // Récupérer les auteurs depuis la base de données
        $authors = $doctrine->getRepository(Author::class)->findAll();

        // Obtenir un message positif aléatoire
        $happyMessage = $happyQuote->getHappyMessage();

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
            'happyMessage' => $happyMessage
        ]);
    }
}