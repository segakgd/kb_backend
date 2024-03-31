<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Handler\Chain\AbstractChain;
use App\Service\System\Resolver\Dto\Contract;

class VariantCount extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function success(Contract $contract, CacheDto $cacheDto): bool
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

        $contractMessage = MessageHelper::createContractMessage(
            $message,
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы... мб вам...',
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

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
