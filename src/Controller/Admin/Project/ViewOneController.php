<?php

declare(strict_types=1);

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Entity\User\Project;
use App\Service\Common\Statistic\StatisticsService;
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
        type: ProjectRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(private readonly StatisticsService $statisticsService) {}

    /**
     * @throws Exception
     */
    #[Route('/api/admin/project/{project}/', name: 'admin_project_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $fakeStatisticsByProject = $this->statisticsService->getStatisticForProject(); // todo use or remove

        return $this->json(
            ProjectRespDto::mapFromEntity($project)
        );
    }
}
