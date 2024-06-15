<?php

namespace App\Service\Constructor\Items\old;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Ecommerce\ProductVariant;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Constructor\Core\Dto\Responsible;

readonly class ShopProductVariantChain // extends AbstractChai
{
    public function __construct(
        private ProductService $productService,
    ) {
    }

    public function success(Responsible $responsible, CacheDto $cacheDto): bool
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

            $responsibleMessage = MessageHelper::createResponsibleMessage(
                'Вы выбрали вариант: ' . $variant->getName() . "\n\n" . 'Выберите желаемое количество или отправьте вручную: ',
            );

            $responsibleMessage->setKeyBoard($replyMarkups);

            $responsible->getResult()->addMessage($responsibleMessage);

            return true;
        }

        return false;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Не понимаю о чем вы, выберите один из вариантов:',
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
