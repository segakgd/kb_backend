<?php

declare(strict_types=1);

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\Response\ProjectResponse;
use App\Controller\GeneralAbstractController;
use App\Entity\User\User;
use App\Repository\Dto\PaginationCollection;
use App\Service\Common\Project\Dto\SearchProjectDto;
use App\Service\Common\Project\ProjectServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Project')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию проектов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: ProjectResponse::class
            )
        )
    ),
)]
class ViewAllController extends GeneralAbstractController
{
    public function __construct(
        private readonly ProjectServiceInterface $projectService,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
    ) {
        parent::__construct($serializer, $validator);
    }

    #[Route('/api/admin/project/', name: 'admin_project_get_all', methods: ['GET'])]
    public function execute(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException('Access Denied.');
        }

        $searchProjectDto = new SearchProjectDto(
            null,
            1,
        );

        $paginateCollection = $this->projectService->search(
            $user,
            $searchProjectDto,
        );

        return $this->json(
            static::makePaginateResponse(
                (new ProjectResponse())->mapCollection(
                    $paginateCollection->getItems()
                ),
                $paginateCollection->getPaginate()
            )
        );
    }
}
