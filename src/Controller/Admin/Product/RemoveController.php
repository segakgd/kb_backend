<?php

namespace App\Controller\Admin\Product;

use App\Controller\Admin\Product\Exception\NotFoundProductForProjectException;
use App\Entity\Ecommerce\Product;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Product\Manager\ProductManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function execute(Project $project, Product $product): JsonResponse
    {
        try {
            if ($product->getProjectId() !== $project->getId()) {
                throw new NotFoundProductForProjectException();
            }

            $this->productManager->remove($product);

            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
