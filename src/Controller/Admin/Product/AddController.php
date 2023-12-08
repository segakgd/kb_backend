<?php

namespace App\Controller\Admin\Product;

use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductCategory;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Product')]
class AddController extends AbstractController
{
    #[Route('/api/admin/project/{project}/product/{product}/category/{productCategory}/',
        name: 'admin_product_add_in_category',
        methods: ['POST'])
    ]
    #[IsGranted('existUser', 'project')]
    public function execute(Product $product, ProductCategory $productCategory): JsonResponse
    {
        return new JsonResponse();
    }
}
