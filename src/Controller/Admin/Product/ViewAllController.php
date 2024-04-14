<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product;

use App\Controller\Admin\Product\DTO\Response\ProductRespDto;
use App\Entity\User\Project;
use App\Helper\Ecommerce\Product\ProductHelper;
use App\Service\Admin\Ecommerce\Product\Manager\ProductManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию товаров',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: ProductRespDto::class
            )
        )
    ),
)]
#[OA\Tag(name: 'Product')]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly ProductManagerInterface $productManager,
    ) {
    }

    /** Получение всех продуктов */
    #[Route('/api/admin/project/{project}/product/', name: 'admin_product_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        try {
            return $this->json(ProductHelper::mapArrayToResponse($this->productManager->getAll($project)));
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
