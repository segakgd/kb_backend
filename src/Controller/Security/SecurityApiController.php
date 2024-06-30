<?php

namespace App\Controller\Security;

use App\Controller\GeneralController;
use App\Controller\Security\DTO\AuthDto;
use App\Dto\Security\UserDto;
use App\Exception\Security\UserExistException;
use App\Service\Common\Security\SecurityService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityApiController extends GeneralController
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

    #[Route('/api/user/authenticate/', name: 'api_auth', methods: ['POST'])]
    public function apiAuth(Request $request): JsonResponse
    {
        try {
            $requestDto = $this->getValidDtoFromRequest($request, AuthDto::class);

            $user = $this->securityService->identifyUser($requestDto);

            return new JsonResponse(
                [
                    'access_token' => $this->securityService->refreshAccessToken($user),
                ]
            );
        } catch (InvalidPasswordException | UserNotFoundException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Exception) {
            return new JsonResponse('Bad request.', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @throws Exception
     */
    #[Route('/api/user/registration/', name: 'visitor_registration', methods: 'POST')]
    public function exist(Request $request): JsonResponse
    {
        $userDto = $this->getValidDtoFromRequest($request, UserDto::class);

        try {
            $user = $this->securityService->createUser($userDto);
        } catch (UserExistException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($user, 200, [], ['groups' => ['openForReading']]);
    }
}
