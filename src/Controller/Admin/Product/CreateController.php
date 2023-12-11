<?php

namespace App\Controller\Admin\Product;

use App\Entity\User\Project;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Product', description: "asdasd")]
class CreateController extends AbstractController
{
    /** Создание продукта */
    #[Route('/api/admin/project/{project}/product/', name: 'admin_product_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse();
    }
}
