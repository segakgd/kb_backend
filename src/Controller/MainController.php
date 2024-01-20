<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function main(): Response
    {
        return new Response;
    }
}
