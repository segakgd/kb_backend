<?php

namespace App\Controller\Admin\Project;

use App\Dto\Admin\Project\Request\ProjectSettingUpdateReqDto;
use App\Dto\Admin\Project\Response\ProjectSettingRespDto;
use App\Entity\User\Project;
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
        type: ProjectSettingUpdateReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает обновлённые нестройки',
    content: new Model(
        type: ProjectSettingRespDto::class,
    )
)]
class UpdateSettingController extends AbstractController
{
    #[Route('/api/admin/projects/{project}/setting', name: 'admin_project_update', methods: ['PUT'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse(
            new ProjectSettingRespDto()
        );
    }
}
