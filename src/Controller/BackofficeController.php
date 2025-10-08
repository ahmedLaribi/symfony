<?php
// src/Controller/BackofficeController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice')]
class BackofficeController extends AbstractController
{
    #[Route('/', name: 'backoffice_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $userCount = $em->getRepository(User::class)->count([]);
        $productCount = 0; // Remplacez par votre logique métier

        return $this->render('backoffice/dashboard.html.twig', [
            'user_count' => $userCount,
            'product_count' => $productCount,
        ]);
    }

    #[Route('/users', name: 'backoffice_users')]
    public function users(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('backoffice/users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/new', name: 'backoffice_users_new')]
    public function newUser(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès');
            return $this->redirectToRoute('backoffice_users');
        }

        return $this->render('backoffice/users/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}