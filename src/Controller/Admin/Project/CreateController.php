<?php

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\DTO\Request\ProjectCreateReqDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Project')]
#[OA\RequestBody(
    content: new Model(
        type: ProjectCreateReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании новго проекта',
)]
class CreateController extends AbstractController
{
    #[Route('/api/admin/projects/', name: 'admin_project_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
