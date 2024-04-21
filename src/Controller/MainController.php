<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function main(): Response
    {
        if ($this->getUser()) {
            dd('other view');
//            return $this->render('admin/user/auth.html.twig');
        }

        return $this->render('admin/user/auth.html.twig');
    }
}
