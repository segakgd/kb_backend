<?php

namespace App\Helper;

class CartHelper
{
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
