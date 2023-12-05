<?php

namespace App\Controller\deprecated\Admin\ProductCategory;

use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class GetOneController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryManagerInterface $productCategoryService,
        private readonly SerializerInterface $serializer,
    ) {}

//    #[Route('/api/admin/project/{project}/productCategory/{productCategoryId}/', name: 'admin_product_category_get_one', methods: ['GET'])]
//    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $productCategoryId): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->normalize(
                $this->productCategoryService->getOne($project->getId(), $productCategoryId),
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
