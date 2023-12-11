<?php

namespace App\Controller\Admin\Promotion;

use App\Entity\User\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RemoveController extends AbstractController
{
    #[Route('/api/admin/project/{project}/promotion/{promotionId}/', name: 'admin_promotion_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $promotionId): JsonResponse
    {
        return new JsonResponse();

    }
}
