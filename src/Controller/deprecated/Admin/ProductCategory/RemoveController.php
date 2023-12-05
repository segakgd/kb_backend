<?php

namespace App\Controller\deprecated\Admin\ProductCategory;

use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RemoveController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryManagerInterface $productCategoryService,
    ) {}

//    #[Route('/api/admin/project/{project}/productCategory/{productCategoryId}/', name: 'admin_product_category_remove', methods: ['DELETE'])]
//    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $productCategoryId): JsonResponse
    {
        $this->productCategoryService->remove($project->getId(),  $productCategoryId);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
