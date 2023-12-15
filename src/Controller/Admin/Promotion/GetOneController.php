<?php

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\DTO\Response\FullPromotionRespDto;
use App\Entity\User\Project;
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
    description: '', // todo You need to write a description
    content: new Model(
        type: FullPromotionRespDto::class
    ),
)]
class GetOneController extends AbstractController
{
    #[Route('/api/admin/project/{project}/promotion/{promotionId}/', name: 'admin_promotion_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $promotionId): JsonResponse
    {
        return new JsonResponse();

    }
}
