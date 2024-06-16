<?php

namespace App\Service\Constructor\Items;

use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\Condition;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class VariantProductChain extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();
        $message = static::class . "Кликнул ты вот это: $content";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        $variantId = $responsible->getCacheDto()->getEvent()->getData()->getVariantId();

        $variant = $this->productService->findVariant($variantId);

        if (null === $variant) {
            throw new Exception('Variant not found');
        }

        $count = $variant->getCount();

        $keyBoards = [];

        for ($i = 1; $count > $i; $i++) {
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
    public function perform(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
