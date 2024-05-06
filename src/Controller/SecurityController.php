<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login-admin', name: 'login_admin', methods: ['GET'])]
    public function login(): Response
    {
        return $this->render('admin/user/auth.html.twig');
    }

    #[Route('/login_check', name: 'app_login_check', methods: ['POST'])]
    public function loginCheck(): void
    {
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(Request $request): Response
    {
        $request->getSession()->invalidate();

        return $this->redirectToRoute('login_admin_form');
    }

    #[Route('/api/user/authenticate/', name: 'app_login.authenticate', methods: ['POST'])]
    public function loginAuthenticate(Request $request, JWTTokenManagerInterface $JWTManager, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Check if email and password are present in the request
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new BadCredentialsException('Email and password are required.');
        }

        // Retrieve user by email
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
//        dd($user);
        // Check if user exists and password is correct
        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            throw new BadCredentialsException('Invalid email or password.');
        }

        // Generate JWT tokens
        $accessToken = $JWTManager->create($user); // Generate access token
        $refreshToken = $JWTManager->create($user); // Generate refresh token

        $refreshEntity = (new RefreshToken())
            ->setRefreshToken($refreshToken)
            ->setValid(new DateTime('+1 day'))
            ->setUsername($user->getId())
        ;

        $entityManager->persist($refreshEntity);
        $entityManager->flush();

        // Return tokens in response
        return new JsonResponse([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ]);
    }
}
