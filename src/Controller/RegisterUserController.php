<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Message\UserRegistered;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterUserController extends AbstractController
{
    #[Route('/register', name: 'register_user', methods: ['GET'])]
    public function renderForm(): Response
    {
        return $this->render('register/create.html.twig');
    }

    #[Route('/register', name: 'register_user_handle', methods: ['POST'])]
    public function handleForm(Request $request, EntityManagerInterface $entityManager, MessageBusInterface $messageBus): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (!$email || !$password) {
            $this->addFlash('error', 'Email and password are required');
            return $this->redirectToRoute('register_user');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

        $entityManager->persist($user);
        $entityManager->flush();

        $messageBus->dispatch(new UserRegistered($user->getId(), $user->getEmail()));

        $this->addFlash('success', 'User registered successfully');
        return $this->redirectToRoute('register_user');
    }
}
