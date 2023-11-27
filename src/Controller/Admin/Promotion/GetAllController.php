<?php

namespace App\Controller\Admin\Promotion;

use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Promotion\PromotionManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class GetAllController extends AbstractController
{
    public function __construct(
        private PromotionManagerInterface $promotionService,
        private SerializerInterface $serializer,
    ) {}

    #[Route('/api/admin/project/{project}/promotion/', name: 'admin_promotion_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->normalize(
                $this->promotionService->getAll($project->getId()),
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
