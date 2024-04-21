<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login-admin', name: 'login_admin', methods: "POST")]

    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        // Получение последнего введенного имени пользователя
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($this->getUser()) {
            dd($this->getUser());
        }

        return $this->render('admin/user/auth.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
}
