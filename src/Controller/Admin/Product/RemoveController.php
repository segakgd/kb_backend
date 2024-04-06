<?php

namespace App\Controller\Admin\Product;

use App\Entity\Ecommerce\Product;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Product\Manager\ProductManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'Product')]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Удаляет продукт',
)]
class RemoveController extends AbstractController
{
    public function __construct(private readonly ProductManagerInterface $productManager)
    {

    }

    /** Удаление одного продукта */
    #[Route('/api/admin/project/{project}/product/{product}/', name: 'admin_product_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ?Product $product): JsonResponse
    {
        if (null === $product) {
            return $this->json('Not found', Response::HTTP_NOT_FOUND);
        }

        if ($product->getProjectId() !== $project->getId()) {
            throw new AccessDeniedException('Access Denied.');
        }

        try {
            $this->productManager->remove($product);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
