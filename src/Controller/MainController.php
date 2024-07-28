<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends GeneralAbstractController
{
    #[Route('/', name: 'login_admin_form', methods: ['GET'])]
    public function main(): Response
    {
        if ($this->getUser()) {
            return new RedirectResponse('/admin/users/');
        }

        return $this->redirectToRoute('app_login');
    }
}
