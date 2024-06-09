<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\common;

use App\Entity\Ecommerce\ProductVariant;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\ResponsibleInterface;

class ShopProductVariantChain extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $cacheDto = $responsible->getCacheDto();
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

            $responsibleMessage = MessageHelper::createResponsibleMessage(
                'Вы выбрали вариант: ' . $variant->getName() . "\n\n" . 'Выберите желаемое количество или отправьте вручную: ',
            );

            $responsibleMessage->setKeyBoard($replyMarkups);

            $responsible->getResult()->addMessage($responsibleMessage);

            return $responsible; // true;
        }

        return $responsible; // false;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
//        $responsibleMessage = MessageHelper::createResponsibleMessage(
//            'Не понимаю о чем вы, выберите один из вариантов:',
//        );
//
//        $responsible->getResult()->addMessage($responsibleMessage);
//
//        return false;

        return true;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Поставить состояние для ' . static::class
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }
}
