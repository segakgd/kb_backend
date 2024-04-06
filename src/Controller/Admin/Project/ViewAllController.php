<?php

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Entity\User\Project;
use App\Entity\User\User;
use App\Service\Admin\Statistic\StatisticsServiceInterface;
use App\Service\Common\Project\ProjectServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    ) {
    }

    #[Route('/api/admin/project/', name: 'admin_project_get_all', methods: ['GET'])]
    public function execute(): JsonResponse
    {
        // todo ВНИМАНИЕ! нужно проерить права пользователя (не гость)

        /** @var User $user */
        $user = $this->getUser();

        $projects = $this->projectService->getAll($user);
        $response = $this->mapToResponse($projects);

        return $this->json($response);
    }

    private function mapToResponse(array $projects): array
    {
        $result = [];

        /** @var Project $project */
        foreach ($projects as $project){
            $fakeStatisticsByProject = $this->statisticsService->getStatisticForProject();

            $result[] = (new ProjectRespDto())
                ->setId($project->getId())
                ->setName($project->getName())
                ->setStatus($project->getStatus())
                ->setStatistic($fakeStatisticsByProject)
                ->setActiveFrom($project->getActiveFrom())
                ->setActiveTo($project->getActiveTo())
            ;
        }

        return $result;
    }
}
