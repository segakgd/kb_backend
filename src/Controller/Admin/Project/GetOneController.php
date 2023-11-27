<?php

namespace App\Controller\Admin\Project;

use App\Entity\User\Project;
use App\Service\Common\Project\ProjectServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class GetOneController extends AbstractController
{
    public function __construct(
        private ProjectServiceInterface $projectService,
        private SerializerInterface $serializer,
    ) {}

    #[Route('/api/admin/projects/{project}/', name: 'admin_project_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->normalize(
                $this->projectService->getOne($project->getId()),
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
