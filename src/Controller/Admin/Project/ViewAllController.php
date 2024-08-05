<?php

declare(strict_types=1);

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Controller\Admin\Project\Response\ProjectsResponse;
use App\Entity\User\User;
use App\Service\Common\Project\ProjectServiceInterface;
use App\Service\Common\Statistic\StatisticsServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly StatisticsServiceInterface $statisticsService,
        private readonly ProjectServiceInterface $projectService,
    ) {}

    #[Route('/api/admin/project/', name: 'admin_project_get_all', methods: ['GET'])]
    public function execute(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException('Access Denied.');
        }

        $projects = $this->projectService->getAll($user);
        $fakeStatisticsByProject = $this->statisticsService->getStatisticForProject();

        return $this->json(
            (new ProjectsResponse())->mapToResponse($projects, $fakeStatisticsByProject)
        );
    }
}
