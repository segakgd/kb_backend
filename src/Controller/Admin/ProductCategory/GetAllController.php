<?php

namespace App\Controller\Admin\ProductCategory;

use App\Entity\User\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GetAllController extends AbstractController
{
    #[Route('/api/admin/project/{project}/productCategory/', name: 'admin_product_category_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse();
    }
}
