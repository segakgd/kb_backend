<?php

namespace App\Controller\Admin\ProductCategory;

use App\Entity\User\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UpdateController extends AbstractController
{
    #[Route('/api/admin/project/{project}/productCategory/{productCategoryId}/', name: 'admin_product_category_update', methods: ['PUT'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $productCategoryId): JsonResponse
    {
        return new JsonResponse();
    }
}
