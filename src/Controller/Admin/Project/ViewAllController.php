<?php

declare(strict_types=1);

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\Request\ProjectSearchRequest;
use App\Controller\Admin\Project\Response\ProjectResponse;
use App\Controller\GeneralAbstractController;
use App\Entity\User\User;
use App\Service\Common\Project\ProjectServiceInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/api/admin/project/', name: 'admin_project_get_all', methods: ['GET'])]
    public function execute(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException('Access Denied.');
        }

        $requestDto = $this->getValidDtoFromFormDataRequest($request, ProjectSearchRequest::class);

        $paginateCollection = $this->projectService->search(
            $user,
            $requestDto,
        );

        $projectsResponse = (new ProjectResponse())->mapCollection(
            $paginateCollection->getItems()
        );

        return $this->json(
            static::makePaginateResponse(
                $projectsResponse,
                $paginateCollection->getPaginate()
            )
        );
    }
}
