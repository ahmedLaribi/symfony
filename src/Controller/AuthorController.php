<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AuthorRepository;
use App\Form\AuthorType;
use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/show/{name}', name:'showAuthor')]
    public function showAuthor($name) {
       return $this->render( 'author/show.html.twig', ['nom'=>$name ,'prenom'=>'ben foulen']);

    }

    #[Route('/showall', name:'showall')]
    Public function showall(AuthorRepository $repo ){
        $author = $repo->findAll();
        return $this->render('author/showall.html.twig', ['list'=>$author]);
    }

    #[Route('/addStat', name:'addStat')]
    Public function addStat(ManagerRegistry $doctrine){
        $author=new Author();
        $author->setEmail('test@gmail.com');
        $author->setUsername('foulen');
        $em=$doctrine->getManager();
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('showall');
    }

    #[Route('/deleteauthor/{id}', name:'deleteauthor')]
    Public function deleteauthor($id,ManagerRegistry $manager, AuthorRepository $repo){
        $author=$repo->find($id);
        $em=$manager->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('showall');
    }

    #[Route('/showAuthorDetails/{id}',name:'showAuthorDetails')]
    public function showAuthorDetails($id, AuthorRepository $repo){
     $author=$repo->find($id);
     return $this->render('author/showAuthorDetails.html.twig',['author'=>$author]);
    }

    #[Route('/addform',name:'addform')]
    public function addform(ManagerRegistry $doctrine){
        $author=new Author();
        $form=$this->createForm(AuthorType::class,$author);
        $form->add('Ajouter', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class);
        $form->handleRequest(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
        if($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist($author);
            $em->flush();
        }
        return $this->render('author/addform.html.twig',['formAuthor'=>$form->createView()]);

        

    }

 





}