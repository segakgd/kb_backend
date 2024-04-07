<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductCategory;

use App\Controller\Admin\ProductCategory\DTO\Response\ProductCategoryRespDto;
use App\Entity\User\Project;
use App\Helper\Ecommerce\ProductCategoryHelper;
use App\Service\Admin\Ecommerce\ProductCategory\Manager\ProductCategoryManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'ProductCategory')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию категорий продуктов проекта',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: ProductCategoryRespDto::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryManagerInterface $productCategoryManager,
    ) {
    }

    /** Получение колекции категорий */
    #[Route('/api/admin/project/{project}/productCategory/', name: 'admin_product_category_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return $this->json(
            ProductCategoryHelper::mapArrayToResponse(($this->productCategoryManager->getAll($project)))
        );
    }
}
