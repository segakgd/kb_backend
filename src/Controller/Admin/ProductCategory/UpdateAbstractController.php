<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductCategory;

use App\Controller\Admin\ProductCategory\DTO\Request\ProductCategoryReqDto;
use App\Controller\Admin\ProductCategory\Exception\NotFoundProductCategoryForProjectException;
use App\Controller\GeneralAbstractController;
use App\Entity\Ecommerce\ProductCategory;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\ProductCategory\Manager\ProductCategoryManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[OA\Tag(name: 'ProductCategoryChain')]
#[OA\RequestBody(
    content: new Model(
        type: ProductCategoryReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Обновляет категорию продукта',
)]
class UpdateAbstractController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ProductCategoryManagerInterface $productCategoryManager
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /** Обновление категории продуктов */
    #[Route('/api/admin/project/{project}/productCategory/{productCategory}/', name: 'admin_product_category_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, ProductCategory $productCategory): JsonResponse
    {
        try {
            if ($productCategory->getProjectId() !== $project->getId()) {
                throw new NotFoundProductCategoryForProjectException();
            }

            $requestDto = $this->getValidDtoFromRequest($request, ProductCategoryReqDto::class);

            $this->productCategoryManager->update($requestDto, $productCategory, $project);

            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
