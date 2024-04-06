<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Ecommerce\ProductVariant;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Resolver\Dto\Contract;

class ShopProductVariantChain extends AbstractChai
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function success(Contract $contract, CacheDto $cacheDto): bool
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
                        'text' => 'вернуться к товарам'
                    ],
                ]
            ];

            $contractMessage = MessageHelper::createContractMessage(
                'Вы выбрали вариант: ' . $variant->getName() . "\n\n" . 'Выберите желаемое количество или отправьте вручную: ',
            );

            $contractMessage->setKeyBoard($replyMarkups);

            $contract->getResult()->addMessage($contractMessage);

            return true;
        }

        return false;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы, выберите один из вариантов:',
        );

        $contract->getResult()->addMessage($contractMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
