<?php

namespace App\Controller\Admin\Project;

use App\Dto\Admin\Project\ProjectCreateDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class CreateController extends AbstractController
{
    #[OA\RequestBody(
        content: new Model(
            type: ProjectCreateDto::class,
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Возвращает 204 при создании новго проекта',
    )]
    #[OA\Tag(name: 'Project')]
    #[Security(name: 'Bearer')]
    #[Route('/api/admin/projects/', name: 'admin_project_create', methods: ['POST'])]
    public function execute(): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
