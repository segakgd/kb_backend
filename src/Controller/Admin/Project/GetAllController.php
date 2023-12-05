<?php

namespace App\Controller\Admin\Project;


use App\Dto\Admin\Project\ProjectCreateDto;
use App\Dto\Admin\Project\ProjectDto;
use App\Entity\User\User;
use App\Service\Common\Project\ProjectServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

class GetAllController extends AbstractController
{
    public function __construct(
        private readonly ProjectServiceInterface $projectService,
        private readonly SerializerInterface $serializer,
    ) {}

    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model(
                    type: ProjectDto::class
                )
            )
        )

    )]
//    #[OA\Response(
//        response: Response::HTTP_NO_CONTENT,
//        description: 'Возвращает 204 при создании новго проекта',
//    )]
    #[OA\Tag(name: 'Project')]
    #[Route('/api/admin/projects/', name: 'admin_project_get_all', methods: ['GET'])]
    public function execute(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse(
            $this->serializer->normalize(
                $this->projectService->getAll($user),
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
