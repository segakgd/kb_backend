<?php

namespace App\Service\System\Resolver\Chains\Items\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Resolver\Dto\Responsible;

class VariantCount// extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $count = $cacheDto->getContent();

        $cacheDto->getEvent()->getData()->setCount($count);

        $variant = $this->productService->findVariant($cacheDto->getEvent()->getData()->getVariantId());
        $product = $variant->getProduct();

        $variantName = $variant->getName();
        $productName = $product->getName();

        $price = $variant->getPrice();
        $price = $price['price'];

        $sum = $price * $count;

        $cartProduct = [
            'productId' => $product->getId(),
            'variantId' => $variant->getId(),
            'count' => $count,
        ];

        $cart = $cacheDto->getCart();
        $cart->addProduct($cartProduct);

        $message = 'Вы добавили в корзину продукт: ' . $productName . "\n" .
            'вариант: ' . $variantName . "\n" .
            'количество: ' . $count . "\n" .
            'сумма: ' . $sum;

        $replyMarkups = [
            [
                [
                    'text' => 'вернуться к товарам'
                ],
                [
                    'text' => 'вернуться к категориям'
                ],
            ],
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
                [
                    'text' => 'Моя корзина'
                ],
            ],
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            $message,
            null,
            $replyMarkups,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return true;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Не понимаю о чем вы... мб вам...',
            null,
            $replyMarkups,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        $available = [1, 2, 3, 4, 5];

        if (in_array($content, $available)) {
            return true;
        }

        return false;
    }
}
