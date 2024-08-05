<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductCategory;

use App\Controller\Admin\ProductCategory\Exception\NotFoundProductCategoryForProjectException;
use App\Entity\Ecommerce\ProductCategory;
use App\Entity\User\Project;
use App\Service\Common\Ecommerce\ProductCategory\Manager\ProductCategoryManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'ProductCategoryChain')]
class RemoveController extends AbstractController
{
    public function __construct(private readonly ProductCategoryManagerInterface $productCategoryManager) {}

    /** Удаление категории продуктов */
    #[Route('/api/admin/project/{project}/productCategory/{productCategory}/', name: 'admin_product_category_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ProductCategory $productCategory): JsonResponse
    {
        try {
            if ($productCategory->getProjectId() !== $project->getId()) {
                throw new NotFoundProductCategoryForProjectException();
            }

            $this->productCategoryManager->remove($productCategory);

            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
