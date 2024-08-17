<?php

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\Request\ProjectCreateRequest;
use App\Controller\Admin\Project\Response\ProjectResponse;
use App\Controller\GeneralAbstractController;
use App\Repository\User\UserRepository;
use App\Service\Common\Project\ProjectServiceInterface;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Project')]
#[OA\RequestBody(
    content: new Model(
        type: ProjectCreateRequest::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Создание проекта',
)]
class CreateController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ProjectServiceInterface $projectService,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /**
     * @throws Exception
     */
    #[Route('/api/admin/project/', name: 'admin_project_create', methods: ['POST'])]
    public function execute(Request $request): JsonResponse
    {
        if (null === $this->getUser()) {
            return $this->json([], Response::HTTP_FORBIDDEN);
        }

        $requestDto = $this->getValidDtoFromRequest($request, ProjectCreateRequest::class);

        $user = $this->userRepository->find($this->getUser());

        if (null === $user) {
            return $this->json([], Response::HTTP_FORBIDDEN);
        }

        $project = $this->projectService->add($requestDto, $user);

        return $this->json($this->serializer->normalize(
            ProjectResponse::mapFromEntity($project)
        ));
    }
}
