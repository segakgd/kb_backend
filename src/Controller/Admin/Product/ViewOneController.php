<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product;

use App\Controller\Admin\Product\DTO\Response\ProductRespDto;
use App\Controller\Admin\Product\Exception\NotFoundProductForProjectException;
use App\Controller\Admin\Product\Response\ProductViewOneResponse;
use App\Entity\Ecommerce\Product;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'Product')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает продукт',
    content: new Model(
        type: ProductRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    /** Получение одного продукта */
    #[Route('/api/admin/project/{project}/product/{product}/', name: 'admin_product_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Product $product): JsonResponse
    {
        try {
            if ($product->getProjectId() !== $project->getId()) {
                throw new NotFoundProductForProjectException();
            }

            return $this->json(
                (new ProductViewOneResponse())->makeResponse($product)
            );
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
