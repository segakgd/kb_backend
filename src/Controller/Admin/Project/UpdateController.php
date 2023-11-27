<?php

namespace App\Controller\Admin\Project;

use App\Dto\Project\ProjectDto;
use App\Entity\User\Project;
use App\Service\Common\Project\ProjectServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateController extends AbstractController
{
    public function __construct(
        private readonly ProjectServiceInterface $projectService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/admin/projects/{project}/', name: 'admin_project_update', methods: ['PUT'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();
        $projectDto = $this->serializer->deserialize($content, ProjectDto::class, 'json');

        $errors = $this->validator->validate($projectDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $projectEntity = $this->projectService->update($projectDto, $project->getId());

        return new JsonResponse(
            $this->serializer->normalize(
                $projectEntity,
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
