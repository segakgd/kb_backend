<?php

namespace App\Controller\Admin;

use App\Dto\CartDto;
use App\Dto\Ecommerce\ProductDto;
use App\Entity\User\Project;
use App\Exception\EcommerceException;
use App\Repository\Ecommerce\PromotionRepository;
use App\Repository\Visitor\CartRepository;
use App\Service\Admin\Ecommerce\Deal\DealManagerInterface;
use App\Service\Admin\Ecommerce\Product\ProductManagerInterface;
use App\Service\Common\Project\ProjectServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CartController extends AbstractController
{
    public function __construct(
        private readonly DealManagerInterface $dealService,
        private readonly ProductManagerInterface $productService,
        private readonly CartRepository $cartRepository,
        private readonly PromotionRepository $promotionRepository,
        private readonly ProjectServiceInterface $projectService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

    // пытаемся купить.
    #[Route('/visitor/project/{project}/cart/', name: 'visitor_cart', methods: ['POST'])]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();
        $cartDto = $this->serializer->deserialize($content, CartDto::class, 'json');

        $errors = $this->validator->validate($cartDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $this->checkProduct($cartDto, $project->getId());

        return new JsonResponse('ok');
    }

    private function checkProduct(CartDto $cartDto, int $projectId)
    {
        if (empty($cartDto->getProducts())){
            throw new EcommerceException('В корзине нет продуктов');
        }

        /** @var ProductDto $product */
        foreach ($cartDto->getProducts() as $product){
            if (!$product->getId()) {
                throw new EcommerceException('Неизвестный товар');
            }

            // todo проверить что товар относится к данному проекту

            if (!$this->productService->isExist($product->getId())){ // todo isNotExist
                throw new EcommerceException('Неизвестный товар');
            }

            $productInDB = $this->productService->getOne($projectId, $product->getId());
            $priceInDB = $productInDB->getPrice();

            if ($priceInDB['value'] !== $product->getPrice()->getValue()){
                throw new EcommerceException('Товар изменился');
            }

            // todo проверить что товар в наличии
            // todo проверить что товар доступен для покупки\резервирования
        }
    }
}
