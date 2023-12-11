<?php

namespace App\Controller\Admin\Product;

use App\Entity\User\Project;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Product')]
class UpdateController extends AbstractController
{
    /** Обновление одного продукта */
    #[Route('/api/admin/project/{project}/product/{productId}/', name: 'admin_product_update', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $productId): JsonResponse
    {
        return new JsonResponse();
    }
}
