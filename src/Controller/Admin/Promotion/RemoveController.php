<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\Exception\NotFoundPromotionForProjectException;
use App\Entity\Ecommerce\Promotion;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Promotion\Manager\PromotionManagerInterface;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'Promotion')]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Удаление скидки',
)]
class RemoveController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly PromotionManagerInterface $promotionManager,
    ) {}

    #[Route('/api/admin/project/{project}/promotion/{promotion}/', name: 'admin_promotion_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Promotion $promotion): JsonResponse
    {
        try {
            if ($promotion->getProjectId() !== $project->getId()) {
                throw new NotFoundPromotionForProjectException();
            }

            $this->promotionManager->delete($promotion);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
