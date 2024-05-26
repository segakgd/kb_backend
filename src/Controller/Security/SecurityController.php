<?php

namespace App\Controller\Security;

use App\Controller\Security\DTO\AuthDto;
use App\Service\Common\Security\SecurityService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly SecurityService $securityService,
    ) {
    }

    #[Route('/lslslsls/', name: 'asdasdasdasd', methods: ['POST', 'GET'])]
    public function lslsls(Request $request): JsonResponse
    {
        return new JsonResponse($request->getMethod());
    }

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

    /**
     * @throws Exception
     */
    #[Route('/api/authenticate/', name: 'api_auth', methods: ['POST'])]
    public function apiAuth(Request $request): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, AuthDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->securityService->identifyUser($requestDto);

        return new JsonResponse(
            [
                'access_token' => $this->securityService->refresh($user),
            ]
        );
    }
}
