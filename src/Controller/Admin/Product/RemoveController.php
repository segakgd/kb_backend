<?php

namespace App\Controller\Admin\Product;

use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Product\ProductManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RemoveController extends AbstractController
{
    public function __construct(
        private readonly ProductManagerInterface $productService,
    ) {}

    #[Route('/api/admin/project/{project}/product/{productId}/', name: 'admin_product_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $productId): JsonResponse
    {
        $this->productService->remove($project->getId(),  $productId);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
