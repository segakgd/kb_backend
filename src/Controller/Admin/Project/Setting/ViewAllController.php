<?php

namespace App\Controller\Admin\Project\Setting;

use App\Controller\Admin\Project\DTO\Response\Setting\ProjectSettingRespDto;
use App\Controller\Admin\Project\Response\Setting\ViewAllSettingResponse;
use App\Entity\User\Project;
use App\Service\Common\Project\ProjectSettingServiceInterface;
use App\Service\Common\Project\TariffServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'Project')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает нестройки',
    content: new Model(
        type: ProjectSettingRespDto::class,
    )
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ProjectSettingServiceInterface $projectSettingService,
        private readonly TariffServiceInterface $tariffService,
    ) {}

    #[Route('/api/admin/project/{project}/setting/', name: 'admin_list_project_setting', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $projectSetting = $this->projectSettingService->getSettingForProject($project->getId());

        $tariffId = $projectSetting->getTariffId();

        $tariff = $this->tariffService->getTariffById($tariffId);

        return new JsonResponse(
            $this->serializer->normalize(
                (new ViewAllSettingResponse())->mapToResponse($projectSetting, $tariff)
            )
        );
    }
}
