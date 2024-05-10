<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\DTO\Response\PromotionRespDto;
use App\Entity\Ecommerce\Promotion;
use App\Entity\User\Project;
use App\Helper\Ecommerce\Promotion\PromotionHelper;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Promotion')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает промокод',
    content: new Model(
        type: PromotionRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    #[Route('/api/admin/project/{project}/promotion/{promotion}/', name: 'admin_promotion_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ?Promotion $promotion): JsonResponse
    {
        if (null === $promotion) {
            return $this->json('Not found', Response::HTTP_NOT_FOUND);
        } elseif ($promotion->getProjectId() !== $project->getId()) {
            return $this->json('Promotion does not belong to the project', Response::HTTP_FORBIDDEN);
        }

        return $this->json(PromotionHelper::mapToResponseDto($promotion));
    }
}
