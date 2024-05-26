<?php

namespace App\Controller\Security;

use App\Controller\GeneralController;
use App\Controller\Security\DTO\AuthDto;
use App\Service\Common\Security\SecurityService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends GeneralController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly SecurityService $securityService,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
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
    #[Route('/api/user/authenticate/', name: 'api_auth', methods: ['POST'])]
    public function apiAuth(Request $request): JsonResponse
    {
        $requestDto = $this->getValidDtoFromRequest($request, AuthDto::class);

        $user = $this->securityService->identifyUser($requestDto);

        return new JsonResponse(
            [
                'access_token' => $this->securityService->refresh($user),
                'token' => $this->securityService->refresh($user),
            ]
        );
    }
}
