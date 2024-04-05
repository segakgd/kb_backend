<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use App\Service\Admin\Lead\LeadManager;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/api/admin/project/{project}/lead/{lead}/', name: 'admin_lead_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ?Deal $lead): JsonResponse
    {
        if (null === $lead) {
            return $this->json(['Lead not found'], Response::HTTP_NOT_FOUND);
        }

        if ($lead->getProjectId() !== $project->getId()) {
            throw new AccessDeniedException('Access denied.');
        }

        $this->leadManager->remove($lead);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
