<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\Request\FilterLeadsReqDto;
use App\Controller\Admin\Lead\Response\AllLeadRespDto;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Project;
use App\Service\Common\Lead\LeadManager;
use App\Service\Common\Lead\LeadMapper;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[OA\Tag(name: 'Lead')]
#[OA\RequestBody(
    content: new Model(
        type: FilterLeadsReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию заявок',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: AllLeadRespDto::class
            )
        )
    ),
)]
class ViewAllController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly LeadManager $leadManager,
        private readonly LeadMapper $leadMapper,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /** Получение коллекцию лидов */
    #[Route('/api/admin/project/{project}/lead/', name: 'admin_lead_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        try {
            $requestDto = $this->getValidDtoFromRequest($request, FilterLeadsReqDto::class);

            // todo use filters

            $leads = $this->leadManager->getAllByProject($project);

            return $this->json($this->leadMapper->mapArrayToResponse($leads));
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
