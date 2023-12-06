<?php

namespace App\Controller\Admin\Project\Tariff;

use App\Dto\Admin\Project\TariffSettingRespDto;
use App\Entity\User\Project;
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
    description: 'Возвращает коллекцию доступных тарифов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: TariffSettingRespDto::class
            )
        )
    ),
)]
class AllListController extends AbstractController
{
    #[Route('/api/admin/projects/{project}/setting/tariff', name: 'admin_project_list_tariff', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse(
            [
                new TariffSettingRespDto()
            ]
        );
    }
}
