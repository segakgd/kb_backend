<?php

namespace App\Controller\Admin\Project;

use App\Entity\User\Project;
use App\Service\Common\Project\ProjectServiceInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Project')]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Удаление проекта',
)]
class RemoveController extends AbstractController
{
    public function __construct(
        private readonly ProjectServiceInterface $projectService,
    ) {
    }

    #[Route('/api/admin/project/{project}/', name: 'admin_project_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        // todo по хорошему бы как-то удостовериться в том, что пользователь хочет удалить проект.
        //  К примеру, отправить код на почту, ну или ссылку на удаление и тд.

        $isRemoved = $this->projectService->remove($project->getId());

        if (!$isRemoved){
            return $this->json([], Response::HTTP_CONFLICT);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
