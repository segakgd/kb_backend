<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\Exception\NotFoundLeadForProjectException;
use App\Controller\Admin\Lead\Response\LeadResponse;
use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use App\Service\Common\Lead\LeadMapper;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'Lead')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает заявку',
    content: new Model(
        type: LeadResponse::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly LeadMapper $leadMapper,
    ) {}

    /** Получение одного лида */
    #[Route('/api/admin/project/{project}/lead/{lead}/', name: 'admin_lead_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ?Deal $lead): JsonResponse
    {
        try {
            if ($project->getId() !== $lead->getProjectId()) {
                throw new NotFoundLeadForProjectException();
            }

            return $this->json($this->leadMapper->mapToResponse($lead));
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
