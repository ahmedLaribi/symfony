<?php

namespace App\Controller;

use App\Service\MessageGenerator;
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
   
    
#[Route('/', name: 'home')]
public function home(MessageGenerator $messageGenerator): Response
{
$message = $messageGenerator->getHappyMessage();
return new Response("<h1>Citation du jour :</h1><p>$message</p>");
}
 }
