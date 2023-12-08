<?php

namespace App\Controller\Admin\Product;

use App\Entity\User\Project;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Product')]
class GetAllController extends AbstractController
{
    #[Route('/api/admin/project/{project}/product/', name: 'admin_product_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse();
    }
}
