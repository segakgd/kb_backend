<?php

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\DTO\Request\ProjectUpdateReqDto;
use App\Controller\Admin\Project\Response\ProjectResponse;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Project;
use App\Service\Admin\Statistic\StatisticsServiceInterface;
use App\Service\Common\Project\ProjectServiceInterface;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Project')]
#[OA\RequestBody(
    content: new Model(
        type: ProjectUpdateReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Обновление проекта',
)]
class UpdateAbstractController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ProjectServiceInterface $projectService,
        private readonly StatisticsServiceInterface $statisticsService,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /**
     * Обновление параметров проекта
     *
     * @throws Exception
     */
    #[Route('/api/admin/project/{project}/', name: 'admin_project_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $requestDto = $this->getValidDtoFromRequest($request, ProjectUpdateReqDto::class);

        $project = $this->projectService->update($requestDto, $project);

        $fakeStatisticsByProject = $this->statisticsService->getStatisticForProject();

        return $this->json($this->serializer->normalize(
            (new ProjectResponse())->mapToResponse($project, $fakeStatisticsByProject)
        ));
    }
}
