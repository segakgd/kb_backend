<?php

namespace App\Controller;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class SecurityController extends AbstractController
{
    #[Route('/login-admin', name: 'login_admin', methods: ['GET'])]
    public function login(): Response
    {
        return $this->render('admin/user/auth.html.twig');
    }

    #[Route('/login_check', name: 'app_login_check', methods: ['POST'])]
    public function loginCheck(): void
    {}

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(EventDispatcherInterface $dispatcher, Request $request): Response
    {
        $dispatcher->dispatch(new LogoutEvent($request, null));

        return $this->redirectToRoute('login_admin_form');
    }
}
