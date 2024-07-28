<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\DTO\Response\PromotionRespDto;
use App\Controller\Admin\Promotion\Exception\NotFoundPromotionForProjectException;
use App\Controller\Admin\Promotion\Response\PromotionViewOneResponse;
use App\Entity\Ecommerce\Promotion;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
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
    response: Response::HTTP_OK,
    description: 'Возвращает скидку',
    content: new Model(
        type: PromotionRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    #[Route('/api/admin/project/{project}/promotion/{promotion}/', name: 'admin_promotion_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Promotion $promotion): JsonResponse
    {
        try {
            if ($promotion->getProjectId() !== $project->getId()) {
                throw new NotFoundPromotionForProjectException();
            }

            return $this->json(
                (new PromotionViewOneResponse())->makeResponse($promotion)
            );
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
