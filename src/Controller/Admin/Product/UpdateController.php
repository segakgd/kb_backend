<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product;

use App\Controller\Admin\Product\Exception\NotFoundProductForProjectException;
use App\Controller\Admin\Product\Request\ProductRequest;
use App\Controller\GeneralAbstractController;
use App\Entity\Ecommerce\Product;
use App\Entity\User\Project;
use App\Service\Common\Ecommerce\Product\Manager\ProductManagerInterface;
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

#[OA\Tag(name: 'Product')]
#[OA\RequestBody(
    content: new Model(
        type: ProductRequest::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании',
)]
class UpdateController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ProductManagerInterface $productManager,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /** Обновление одного продукта */
    #[Route('/api/admin/project/{project}/product/{product}/', name: 'admin_product_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, Product $product): JsonResponse
    {
        try {
            if ($product->getProjectId() !== $project->getId()) {
                throw new NotFoundProductForProjectException();
            }

            $requestDto = $this->getValidDtoFromRequest($request, ProductRequest::class);

            $this->productManager->update($requestDto, $product, $project);

            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
