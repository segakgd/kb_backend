<?php

namespace App\Helper;

use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class CartHelper
{
    public static function viewCart(ResponsibleInterface $responsible): string
    {
        $cartProducts = $responsible->getCacheDto()->getCart()->getProducts();

        return static::viewCartFromArray($cartProducts);
    }

    public static function viewCartFromArray(array $cartProducts): string
    {
        $number = 1;

        $message = '';

        foreach ($cartProducts as $cartProduct) {
            $message .= "\n\n"
                . KeyboardHelper::getIconNumber($number) . ' товар: ' . $cartProduct['productName'];
            $message .= "\n"
                . 'Вариант: ' . $cartProduct['variantName'];
            $message .= "\n"
                . 'Цена: ' . $cartProduct['cost'] . 'р. /' . $cartProduct['count'] . 'шт. (' . $cartProduct['amount'] . 'р.)';

            $number++;
        }

        return $message;
    }
}
