<?php

namespace App\Controller\Security;

use App\Dto\Security\UserDto;
use App\Exception\Security\UserExistException;
use App\Service\Common\Security\SecurityService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRegistrationController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private SecurityService $securityService,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/api/user/registration/', name: 'visitor_registration', methods: "POST")]
    public function exist(Request $request): JsonResponse
    {
        $userDto = $this->serializer->deserialize($request->getContent(), UserDto::class, 'json');
        $errors = $this->validator->validate($userDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->securityService->createUser($userDto);
        } catch (UserExistException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $normalizeUser = $this->serializer->normalize(
            $user,
            null,
            [
                'groups' => ['openForReading'],
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true
            ]
        );

        return $this->json($normalizeUser);
    }
}