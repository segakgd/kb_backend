<?php

namespace App\Controller\Security;

use App\Form\LoginFormType;
use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/user/auth.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

//    #[Route('/login-admin', name: 'form_login_admin', methods: ['POST', 'GET'])]
//    public function login(
//        Request $request,
//    ): Response {
//        $form = $this->createForm(LoginFormType::class);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $user = $this->userRepository->findOneBy(
//                [
//                    'email' => $form->get('email')->getData(),
//                ]
//            );
//
//            if (!$user) {
//                throw $this->createNotFoundException();
//            }
//
//            dd('Авторизовываем. . .');
//        }
//
//        return $this->render('admin/user/auth.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
}
