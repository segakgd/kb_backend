<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Contract;

class VariantCount
{
    public function __construct(
        private readonly ProductService $productService,
    )
    {
    }

    public function handle(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        if ($this->checkSystemCondition($content)) {
            $cacheDto->getEvent()->getData()->setCount($content);

            $variant = $this->productService->findVariant($cacheDto->getEvent()->getData()->getVariantId());

            $variantName = $variant->getName();
            $productName = $variant->getProduct()->getName();

            $price = $variant->getPrice();
            $price = $price['price'];

            $sum = $price * $content;

            $message = 'Вы добавили в корзину продукт: ' . $productName . "\n" .
                'вариант: ' . $variantName . "\n" .
                'количество: ' . $content . "\n" .
                'сумма: ' . $sum;

            $replyMarkups = [
                [
                    [
                        'text' => 'вернуться к товарам'
                    ],
                    [
                        'text' => 'вернуться к категориям'
                    ],
                    [
                        'text' => 'вернуться в главное меню'
                    ],
                    [
                        'text' => 'в корзину'
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


    private function checkSystemCondition(string $content): bool
    {
        $available = [1, 2, 3, 4, 5, 'вернуться в главное меню', 'вернуться в к товарам'];

        if (in_array($content, $available)) {
            return true;
        }

        return false;
    }
}
