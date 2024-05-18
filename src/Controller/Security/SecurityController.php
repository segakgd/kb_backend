<?php

namespace App\Controller\Security;

use App\Entity\User\RefreshToken;
use App\Entity\User\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/user/auth.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): RedirectResponse
    {
        return new RedirectResponse("/");
    }

    #[Route('/api/user/authenticates/', name: 'app_login.authenticate', methods: ['POST', 'GET'])]
    public function loginAuthenticate(
        Request $request,
        JWTTokenManagerInterface $JWTManager,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            throw new BadCredentialsException('Email and password are required.');
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            throw new BadCredentialsException('Invalid email or password.');
        }

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
