<?php

namespace App\Controller\Security;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login-admin', name: 'form_login_admin', methods: ['POST', 'GET'])]
    public function login(
        Request $request,
    ): Response {
        $form = $this->createForm(LoginFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd('Авторизовываем. . .');
        }

        return $this->render('admin/user/auth.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
