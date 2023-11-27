<?php

namespace App\Controller\Admin\ProductCategory;

use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class GetAllController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryManagerInterface $productCategoryService,
        private readonly SerializerInterface $serializer,
    ) {}

    #[Route('/api/admin/project/{project}/productCategory/', name: 'admin_product_category_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->normalize(
                $this->productCategoryService->getAll($project->getId()),
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
