<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductCategory;

use App\Controller\Admin\ProductCategory\DTO\Response\ProductCategoryRespDto;
use App\Entity\Ecommerce\ProductCategory;
use App\Entity\User\Project;
use App\Helper\Ecommerce\Product\ProductCategoryHelper;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'ProductCategory')]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает запрашиваемую категорию по проекту',
    content: new Model(
        type: ProductCategoryRespDto::class,
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct()
    {
    }

    /** Получение категории продуктов */
    #[Route('/api/admin/project/{project}/productCategory/{productCategory}/', name: 'admin_product_category_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ?ProductCategory $productCategory): JsonResponse
    {
        if (null === $productCategory) {
            return $this->json('Not found', Response::HTTP_NOT_FOUND);
        }

        if ($productCategory->getProjectId() !== $project->getId()) {
            throw new AccessDeniedException('Access Denied.');
        }

        return $this->json(ProductCategoryHelper::mapToResponse($productCategory));
    }
}
