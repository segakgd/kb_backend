<?php

namespace App\Controller\Admin\Project\Setting;

use App\Controller\Admin\Project\DTO\Response\Setting\ProjectSettingRespDto;
use App\Entity\User\Project;
use App\Service\Common\Project\ProjectSettingServiceInterface;
use Exception;
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
    description: 'Возвращает нестройки',
    content: new Model(
        type: ProjectSettingRespDto::class,
    )
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly ProjectSettingServiceInterface $projectSettingService,
    ) {}

    /**
     * @throws Exception
     */
    #[Route('/api/admin/project/{project}/settings/', name: 'admin_list_project_setting', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $projectSetting = $this->projectSettingService->getSettingForProject($project->getId());

        return $this->json(
            ProjectSettingRespDto::mapFromEntity($projectSetting)
        );
    }
}
