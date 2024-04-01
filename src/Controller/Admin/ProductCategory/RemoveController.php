<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductCategory;

use App\Entity\Ecommerce\ProductCategory;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\ProductCategory\Manager\ProductCategoryManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'ProductCategory')]
class RemoveController extends AbstractController
{
    public function __construct(private readonly ProductCategoryManagerInterface $productCategoryManager)
    {
    }

    #[Route('/api/admin/project/{project}/productCategory/{productCategory}/', name: 'admin_product_category_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ?ProductCategory $productCategory): JsonResponse
    {
        if (null === $productCategory) {
            return $this->json(['Not found'], Response::HTTP_NOT_FOUND);
        }

        if ($productCategory->getProjectId() !== $project->getId()) {
            throw new AccessDeniedException('Access denied.');
        }

        try {
            $this->productCategoryManager->remove($productCategory);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
