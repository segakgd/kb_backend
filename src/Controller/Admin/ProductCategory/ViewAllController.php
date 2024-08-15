<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductCategory;

use App\Controller\Admin\Product\DTO\Response\ProductCategoryResponse;
use App\Controller\Admin\ProductCategory\Response\ProductCategoryResponse;
use App\Entity\User\Project;
use App\Service\Common\Ecommerce\ProductCategory\Manager\ProductCategoryManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'ProductCategory')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию категорий продуктов проекта',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: ProductCategoryResponse::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryManagerInterface $productCategoryManager,
    ) {}

    /** Получение коллекции категорий */
    #[Route('/api/admin/project/{project}/product-categories/', name: 'admin_product_category_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        try {
            return $this->json(
                ProductCategoryResponse::mapFromCollection(
                    $this->productCategoryManager->getAll($project)
                )
            );
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
