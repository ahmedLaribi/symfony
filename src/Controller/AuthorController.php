<?php

namespace App\Controller;
use App\Repository\AuthorRepository;
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


    #[Route('/showall',name:'showall')]
    public function showall(AuthorRepository $repo){
        $authors=$repo->findAll();
        return $this->render('author/showall.html.twig',['list'=>$authors]);
    }
}
