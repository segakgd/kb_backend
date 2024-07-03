<?php

namespace App\Controller\Security;

use App\Controller\GeneralController;
use App\Controller\Security\DTO\AuthDto;
use App\Controller\Security\DTO\ReloadAccessDto;
use App\Dto\Security\UserDto;
use App\Exception\Security\UserExistException;
use App\Service\Common\Security\SecurityService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\AccessToken\Oidc\Exception\InvalidSignatureException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityApiController extends GeneralController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly SecurityService $securityService,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    #[Route('/api/user/authenticate/', name: 'api_auth', methods: ['POST'])]
    public function apiAuth(Request $request): JsonResponse
    {
        $requestDto = $this->getValidDtoFromRequest($request, AuthDto::class);

        try {

            $user = $this->securityService->identifyUser($requestDto);

            return new JsonResponse(
                [
                    'accessToken'   => $this->securityService->refreshAccessToken($user),
                    'refreshTokens' => $user->getRefreshTokens(),
                ]
            );
        } catch (InvalidPasswordException | UserNotFoundException $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());

            return new JsonResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());

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
            $this->logger->error($exception->getMessage(), $exception->getTrace());

            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());

            return new JsonResponse('Bad request.', Response::HTTP_BAD_REQUEST);
        }

        return $this->json($user, 200, [], ['groups' => ['openForReading']]);
    }

    #[Route('/api/user/reload-access/', name: 'reload_access', methods: 'POST')]
    public function reloadAccess(Request $request): JsonResponse
    {
        /** @var ReloadAccessDto $reloadAccessDto */
        $reloadAccessDto = $this->getValidDtoFromRequest($request, ReloadAccessDto::class);

        try {
            $accessToken = $this->securityService->reloadAccess($reloadAccessDto);
        } catch (InvalidSignatureException $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());

            return $this->json(['message' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());

            return new JsonResponse('Bad request.', Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(
            [
                'accessToken' => $accessToken,
            ]
        );
    }
}
