<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product;

use App\Controller\Admin\Product\DTO\Response\ProductRespDto;
use App\Entity\Ecommerce\Product;
use App\Entity\User\Project;
use App\Helper\Ecommerce\Product\ProductHelper;
use Nelmio\ApiDocBundle\Annotation\Model;
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
    response: Response::HTTP_OK,
    description: 'Возвращает продукт',
    content: new Model(
        type: ProductRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct()
    {
    }

    /** Получение одного продукта */
    #[Route('/api/admin/project/{project}/product/{product}/', name: 'admin_product_get_one', methods: ['GET'])]
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
            return $this->json(ProductHelper::mapToResponse($product));
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
