<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Controller\GeneralController;
use App\Dto\Security\UserDto;
use App\Exception\Security\UserExistException;
use App\Service\Common\Security\SecurityService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRegistrationController extends GeneralController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly SecurityService $securityService,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /**
     * @throws Exception
     */
    #[Route('/api/user/registration/', name: 'visitor_registration', methods: "POST")]
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
