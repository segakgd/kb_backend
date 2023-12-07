<?php

namespace App\Controller\Admin\Lead;

use App\Entity\User\Project;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UpdateController extends AbstractController
{
    #[OA\Tag(name: 'Lead')]
    #[Route('/api/admin/project/{project}/lead/{leadId}/', name: 'admin_lead_update', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $leadId): JsonResponse
    {
        return new JsonResponse(); // todo пока что оставил, позже сделаем этот функционал
    }
}
