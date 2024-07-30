<?php

namespace App\Service\Constructor\Actions\Ecommerce;

use App\Helper\CartHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class VariantProductChain extends AbstractChain
{
    public static function getName(): string
    {
        return '';
    }

    public function __construct(
        private readonly ProductService $productService,
    ) {}

    /**
     * @throws Exception
     */
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getContent();
        $variantId = $responsible->getEvent()->getData()->getVariantId();

        $variant = $this->productService->findVariant($variantId);

        $variantPrice = $variant->getPrice()[0]->getPrice();

        $amount = $variantPrice * (int) $content;

        $responsible->getCart()->addProduct(
            [
                'productName' => $variant->getProduct()->getName(),
                'variantName' => $variant->getName(),
                'cost'        => $variant->getPrice()[0]->getPrice(),
                'amount'      => $amount,
                'count'       => $content,
            ]
        );

        $cartProducts = $responsible->getCart()->getProducts();

        $totalAmount = 0;

        foreach ($cartProducts as $cartProduct) {
            $totalAmount += $cartProduct['amount'];
        }

        $responsible->getCart()->setTotalAmount($totalAmount);

        // todo списываем с магазина товары

        $responsible->getCart()->setTotalAmount($totalAmount);

        $message = 'Отлично! Товар добавлен в корзину.';

        $message .= CartHelper::viewCartFromArray($cartProducts);
        $message .= "\n\nСумма корзины: $totalAmount р.";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            keyBoard: [
                [
                    [
                        'text' => 'К товарам',
                    ],
                    [
                        'text' => 'К категориям',
                    ],
                ],
                [
                    [
                        'text' => 'В главное меню',
                    ],
                    [
                        'text' => 'Моя корзина',
                    ],
                ],
            ]
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    /**
     * @throws Exception
     */
    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        $variantId = $responsible->getEvent()->getData()->getVariantId();

        $variant = $this->productService->findVariant($variantId);

        if (null === $variant) {
            throw new Exception('Variant not found');
        }

        $count = $variant->getCount();

        $keyBoards = [];

        for ($i = 1; $count >= $i; ++$i) {
            $keyBoards[] = [
                'text' => $i,
            ];
        }

        return $this->makeCondition(
            [
                $keyBoards,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function before(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function after(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
