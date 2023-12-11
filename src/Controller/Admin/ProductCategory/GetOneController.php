<?php

namespace App\Controller\Admin\ProductCategory;

use App\Entity\User\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GetOneController extends AbstractController
{
    #[Route('/api/admin/project/{project}/productCategory/{productCategoryId}/', name: 'admin_product_category_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $productCategoryId): JsonResponse
    {
        return new JsonResponse();
    }
}
