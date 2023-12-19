<?php

namespace App\Controller\Admin\Product;

use App\Entity\User\Project;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Product')]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Удаляет продукт',
)]
class RemoveController extends AbstractController
{
    /** Удаление одного продукта */
    #[Route('/api/admin/project/{project}/product/{productId}/', name: 'admin_product_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $productId): JsonResponse
    {
        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
