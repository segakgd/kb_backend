<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductCategory;

use App\Controller\Admin\ProductCategory\DTO\Request\ProductCategoryReqDto;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\ProductCategory\Manager\ProductCategoryManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[OA\Tag(name: 'ProductCategory')]
#[OA\RequestBody(
    content: new Model(
        type: ProductCategoryReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает созданную категорию',
)]
class CreateController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ProductCategoryManagerInterface $productCategoryManager,
    ) {
    }

    #[Route('/api/admin/project/{project}/productCategory/', name: 'admin_product_category_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        try {
            $requestDto = $this->serializer->deserialize($request->getContent(), ProductCategoryReqDto::class, 'json');

            $errors = $this->validator->validate($requestDto);

            if (count($errors) > 0) {
                return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
            }

            $this->productCategoryManager->create($requestDto, $project);

            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
