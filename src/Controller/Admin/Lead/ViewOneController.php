<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Response\LeadRespDto;
use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use App\Service\Admin\Lead\LeadMapper;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Lead')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает заявку',
    content: new Model(
        type: LeadRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly LeadMapper $leadMapper,
    ) {
    }

    /** Получение одного лида */
    #[Route('/api/admin/project/{project}/lead/{lead}/', name: 'admin_lead_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ?Deal $lead): JsonResponse
    {
        if (null === $lead) {
            return $this->json('Lead not found', Response::HTTP_NOT_FOUND);
        }

        if ($project->getId() !== $lead->getProjectId()) {
            throw new AccessDeniedException('Access Denied.');
        }

        return $this->json($this->leadMapper->mapToResponse($lead));
    }
}
