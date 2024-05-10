<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\DTO\Response\PromotionRespDto;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Promotion\Manager\PromotionManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Project')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию проектов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: PromotionRespDto::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly PromotionManagerInterface $promotionManager,
    ) {
    }

    /** Вывести все скидки и промокоды */
    #[Route('/api/admin/project/{project}/promotion/', name: 'admin_promotion_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return $this->json($this->promotionManager->getAllByProject($project));
    }
}
