<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login-admin', name: 'app_login', methods: ["GET"])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        // Получение последнего введенного имени пользователя
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/user/auth.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
