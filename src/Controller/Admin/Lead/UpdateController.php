<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Request\LeadReqDto;
use App\Controller\Admin\Lead\Exception\NotFoundLeadForProjectException;
use App\Controller\GeneralController;
use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use App\Service\Admin\Lead\LeadManager;
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
        type: LeadReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании',
)]
class UpdateController extends GeneralController
{
    public function __construct(
        private readonly LeadManager $leadManager,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /** Обновление лида */
    #[OA\Tag(name: 'Lead')]
    #[Route('/api/admin/project/{project}/lead/{lead}/', name: 'admin_lead_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, Deal $lead): JsonResponse
    {
        try {
            if ($project->getId() !== $lead->getProjectId()) {
                throw new NotFoundLeadForProjectException();
            }

            $requestDto = $this->getValidDtoFromRequest($request, LeadReqDto::class);

            $this->leadManager->update($requestDto, $lead, $project);

            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
