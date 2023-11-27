<?php

namespace App\Controller\Admin\Project;

use App\Entity\User\Project;
use App\Service\Common\Project\ProjectServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RemoveController extends AbstractController
{
    public function __construct(
        private ProjectServiceInterface $projectService,
    ) {}

    #[Route('/api/admin/projects/{project}/', name: 'admin_project_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $this->projectService->remove($project->getId());

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
