<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductCategory;

use App\Controller\Admin\ProductCategory\Exception\NotFoundProductCategoryForProjectException;
use App\Controller\Admin\ProductCategory\Response\ProductCategoryResponse;
use App\Entity\Ecommerce\ProductCategory;
use App\Entity\User\Project;
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
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает запрашиваемую категорию по проекту',
    content: new Model(
        type: ProductCategoryResponse::class,
    ),
)]
class ViewOneController extends AbstractController
{
    /** Получение категории продуктов */
    #[Route('/api/admin/project/{project}/product-categories/{productCategory}/', name: 'admin_product_category_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ProductCategory $productCategory): JsonResponse
    {
        try {
            if ($productCategory->getProjectId() !== $project->getId()) {
                throw new NotFoundProductCategoryForProjectException();
            }

            return $this->json(
                ProductCategoryResponse::mapFromEntity($productCategory)
            );
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
