<?php

namespace App\Controller\deprecated\Admin\Product;

use App\Dto\deprecated\Ecommerce\ProductDto;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Product\ProductManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateController extends AbstractController
{
    public function __construct(
        private readonly ProductManagerInterface $productService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

//    #[Route('/api/admin/project/{project}/product/', name: 'admin_product_create', methods: ['POST'])]
//    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();
        $productDto = $this->serializer->deserialize($content, ProductDto::class, 'json');

        $errors = $this->validator->validate($productDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $productEntity = $this->productService->add($productDto, $project->getId());

        return new JsonResponse(
            $this->serializer->normalize(
                $productEntity,
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
