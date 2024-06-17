<?php

namespace App\Service\Constructor\Items;

use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Constructor\Core\Chains\AbstractChain;
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
        $variantId = $responsible->getCacheDto()->getEvent()->getData()->getVariantId();

        $variant = $this->productService->findVariant($variantId);

        $amount = $variant->getPrice()['price'] * $content;

        $responsible->getCacheDto()->getCart()->addProduct(
            [
                'productName' => $variant->getProduct()->getName(),
                'variantName' => $variant->getName(),
                'cost' => $variant->getPrice()['price'],
                'amount' => $amount,
                'count' => $content,
            ]
        );

        $cartProducts = $responsible->getCacheDto()->getCart()->getProducts();

        $totalAmount = 0;

        foreach ($cartProducts as $cartProduct) {
            $totalAmount += $cartProduct['amount'];
        }

        $responsible->getCacheDto()->getCart()->setTotalAmount($totalAmount);

        // todo списываем с магазина товары

        $responsible->getCacheDto()->getCart()->setTotalAmount($totalAmount);

        $message = "Отлично! Товар добавлен в корзину."
            . "\n\n"
        ;

        foreach ($cartProducts as $cartProduct) {
            $message .= "\n"
                . "Товар: " . $totalAmount['productName'] . '/' . $cartProduct['variantName']
            ;
            $message .= "\n"
                . "Цена: " . $totalAmount['cost'] . 'р. /' . $cartProduct['count'] . 'шт. (' . $totalAmount['amount'] . 'р.)'
            ;
        }

        $message .= "\n\n"
            . "Сумма корзины: $totalAmount"
        ;

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    /**
     * @throws Exception
     */
    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        $variantId = $responsible->getCacheDto()->getEvent()->getData()->getVariantId();

        $variant = $this->productService->findVariant($variantId);

        if (null === $variant) {
            throw new Exception('Variant not found');
        }

        $count = $variant->getCount();

        $keyBoards = [];

        for ($i = 1; $count >= $i; $i++) {
            $keyBoards[] = [
                'text' => $i,
            ];
        }

        return $this->makeCondition(
            [
                $keyBoards
            ]
        );
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
