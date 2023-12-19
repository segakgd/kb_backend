<?php

namespace App\Controller\Admin\ProductCategory;

use App\Controller\Admin\ProductCategory\DTO\Request\ProductCategoryReqDto;
use App\Entity\User\Project;
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

#[OA\Tag(name: 'ProductCategory')]
#[OA\RequestBody(
    content: new Model(
        type: ProductCategoryReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: '', // todo You need to write a description
)]
class UpdateController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/admin/project/{project}/productCategory/{productCategoryId}/', name: 'admin_product_category_update', methods: ['PUT'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $productCategoryId): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, ProductCategoryReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
