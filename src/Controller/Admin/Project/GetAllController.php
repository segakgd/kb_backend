<?php

namespace App\Controller\Admin\Project;

use App\Dto\Admin\Project\ProjectRespDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Project')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию проектов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: ProjectRespDto::class
            )
        )
    ),
)]
class GetAllController extends AbstractController
{
    #[Route('/api/admin/projects/', name: 'admin_project_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(): JsonResponse
    {
        return new JsonResponse(
            new ProjectRespDto()
        );
    }
}
