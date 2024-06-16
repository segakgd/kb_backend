<?php

namespace App\Service\Constructor\Items;

use App\Entity\Ecommerce\ProductVariant;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\Condition;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class VariantsProductChain extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

        $productId = $responsible->getCacheDto()->getEvent()->getData()->getProductId();
        $product = $this->productService->find($productId);

        $variants = $product->getVariants();

        $variantId = null;

        foreach ($variants as $variant) {
            if ($variant->getName() === $content) {
                $variantId = $variant->getId();
            }
        }

        if (null === $variantId) {
            throw new Exception('Выбранного варианта не существует!');
        }

        $responsible->getCacheDto()->getEvent()->getData()->setVariantId($variantId);

        $message = static::class . "Вы выбрали вариант: $content. \n\n Укажите количество: ";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            keyBoard: $responsible->getNextCondition()->getKeyBoard()
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        $productId = $responsible->getCacheDto()->getEvent()->getData()->getProductId();

        $product = $this->productService->find($productId);

        $variants = $product->getVariants();

        $keyBoards = [];

        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            $keyBoards[] = [
                'text' => $variant->getName()
            ];
        }

        $replyMarkups = [
            $keyBoards
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }

    /**
     * @throws Exception
     */
    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
