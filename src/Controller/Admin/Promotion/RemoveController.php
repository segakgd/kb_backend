<?php

namespace App\Controller\Admin\Promotion;

use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Promotion\PromotionManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RemoveController extends AbstractController
{
    public function __construct(
        private readonly PromotionManagerInterface $promotionService,
    ) {}

    #[Route('/api/admin/project/{project}/promotion/{promotionId}/', name: 'admin_promotion_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $promotionId): JsonResponse
    {
        $this->promotionService->remove($project->getId(),  $promotionId);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
