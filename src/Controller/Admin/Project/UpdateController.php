<?php

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Controller\Admin\Project\Request\ProjectUpdateRequest;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Project;
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
        type: ProjectUpdateRequest::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Обновление проекта',
)]
class UpdateController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ProjectServiceInterface $projectService,
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
        $requestDto = $this->getValidDtoFromRequest($request, ProjectUpdateRequest::class);

        $project = $this->projectService->update($requestDto, $project);

        return $this->json($this->serializer->normalize(
            ProjectRespDto::mapFromEntity($project)
        ));
    }
}
