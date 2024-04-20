<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion;

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
    description: 'Если получилось удалить – возвращаем 204',
)]
class RemoveController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly PromotionManagerInterface $promotionManager) {
    }

    #[Route('/api/admin/project/{project}/promotion/{promotion}/', name: 'admin_promotion_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ?Promotion $promotion): JsonResponse
    {
        if (null === $promotion) {
            return $this->json('Not found', Response::HTTP_NOT_FOUND);
        } elseif ($promotion->getProjectId() !== $project->getId()) {
            return $this->json('Promotion does not belong to the project', Response::HTTP_FORBIDDEN);
        }

        try {
            $this->promotionManager->delete($promotion);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
