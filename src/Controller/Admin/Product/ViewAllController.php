<?php

namespace App\Controller\Admin\Product;

use App\Controller\Admin\Product\DTO\Response\AllProductRespDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию товаров',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: AllProductRespDto::class
            )
        )
    ),
)]
#[OA\Tag(name: 'Product')]
class ViewAllController extends AbstractController
{
    /** Получение всех продуктов */
    #[Route('/api/admin/project/{project}/product/', name: 'admin_product_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse();
    }
}
