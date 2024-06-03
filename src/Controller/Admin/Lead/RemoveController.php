<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\Exception\NotFoundLeadForProjectException;
use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use App\Service\Admin\Lead\LeadManager;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'Lead')]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Если получилось удалить возвращаем 204',
)]
class RemoveController extends AbstractController
{
    public function __construct(private readonly LeadManager $leadManager)
    {
    }

    /** Удаление лида */
    #[Route('/api/admin/project/{project}/lead/{lead}/', name: 'admin_lead_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(?Project $project, Deal $lead): JsonResponse
    {
        try {
            if ($project->getId() !== $lead->getProjectId()) {
                throw new NotFoundLeadForProjectException();
            }

            $this->leadManager->remove($lead);

            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
