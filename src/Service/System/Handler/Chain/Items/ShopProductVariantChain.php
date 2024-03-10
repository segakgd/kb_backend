<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Ecommerce\ProductVariant;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Contract;

class ShopProductVariantChain // 4
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function handle(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        $productId = $cacheDto->getEvent()->getData()->getProductId();
        $product = $this->productService->find($productId);

        $variants = $product->getVariants()->filter(
            function (ProductVariant $variant) use ($content) {
                return $variant->getName() === $content;
            }
        );

        $variant = $variants->first();

        if ($variant) {
            $cacheDto->getEvent()->getData()->setVariantId($variant->getId());

            $replyMarkups = [
                [
                    [
                        'text' => 1
                    ],
                    [
                        'text' => 2
                    ],
                    [
                        'text' => 3
                    ],
                    [
                        'text' => 4
                    ],
                    [
                        'text' => 5
                    ],
                ],
                [
                    [
                        'text' => 'вернуться в главное меню'
                    ],
                    [
                        'text' => 'вернуться в к товарам'
                    ],
                ]
            ];

            $contractMessage = MessageHelper::createContractMessage(
                'Вы выбрали вариант: ' . $variant->getName() . "\n\n" . 'Выберите желаемое количество или отправьте вручную: ',
            );

            $contractMessage->setKeyBoard($replyMarkups);

            $contract->addMessage($contractMessage);

            return true;
        }

        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы, выберите один из вариантов:',
        );

        $contract->addMessage($contractMessage);

        return false;
    }
}
