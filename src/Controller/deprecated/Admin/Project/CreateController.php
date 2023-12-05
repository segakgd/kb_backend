<?php

namespace App\Controller\deprecated\Admin\Project;

use App\Dto\deprecated\Project\ProjectDto;
use App\Entity\User\User;
use App\Service\Common\Project\ProjectServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateController extends AbstractController
{
    public function __construct(
        private readonly ProjectServiceInterface $projectService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

//    #[Route('/api/admin/projects/', name: 'admin_project_create', methods: ['POST'])]
    public function execute(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $projectDto = $this->serializer->deserialize($content, ProjectDto::class, 'json');

        $errors = $this->validator->validate($projectDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->getUser();
        $projectEntity = $this->projectService->add($projectDto, $user);

        return new JsonResponse(
            $this->serializer->normalize(
                $projectEntity,
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
