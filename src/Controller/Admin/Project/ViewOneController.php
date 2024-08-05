<?php

declare(strict_types=1);

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Controller\Admin\Project\Response\ProjectResponse;
use App\Entity\User\Project;
use App\Service\Common\Statistic\StatisticsService;
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

    #[Route('/api/admin/project/{project}/', name: 'admin_project_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $fakeStatisticsByProject = $this->statisticsService->getStatisticForProject();

        return $this->json(
            (new ProjectResponse())->mapToResponse($project, $fakeStatisticsByProject)
        );
    }
}
