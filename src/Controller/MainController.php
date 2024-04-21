<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'login_admin_form', methods: ['GET'])]
    public function main(): Response
    {
        if ($this->getUser()) {
            dd('other view');
        }

//        return new RedirectResponse("/login-admin");
        return new Response('asda');
    }
}
