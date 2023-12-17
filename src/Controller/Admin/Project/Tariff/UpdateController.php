<?php

namespace App\Controller\Admin\Project\Tariff;

use App\Controller\Admin\Project\DTO\Request\TariffSettingReqDto;
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
        type: TariffSettingReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 если новый тариф применён',
)]
class UpdateController extends AbstractController
{
    #[Route('/api/admin/projects/{project}/setting/tariff', name: 'admin_project_update_tariff', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
