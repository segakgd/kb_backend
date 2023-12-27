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
    public function addWebhookAction(): Response
    {
        return new Response(
            '
                <body>
                    <h1 style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-wrap: wrap; align-items: center; justify-content: center; color: #c1c1c1; font-size: 11vw;">Тут нечего делать</h1>
                </body>
            '
        );
    }
}
