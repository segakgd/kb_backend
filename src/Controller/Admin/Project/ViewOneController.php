<?php

declare(strict_types=1);

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\Response\ProjectResponse;
use App\Entity\User\Project;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Bot')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает проект',
    content: new Model(
        type: ProjectResponse::class
    ),
)]
class ViewOneController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/api/admin/project/{project}/', name: 'admin_project_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return $this->json(
            ProjectResponse::mapFromEntity($project)
        );
    }
}
