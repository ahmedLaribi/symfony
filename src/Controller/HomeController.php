<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render(view: 'home/index.html.twig', parameters: [
            'controller_name' => 'HomeController','identifiant'=>5
        ]);
    }

    #[Route(path: '/hello/{id}',name:'hello')]
    public function hello($id): Response{
        return new Response(content:"hello 3a25" .$id);

    }
}
 