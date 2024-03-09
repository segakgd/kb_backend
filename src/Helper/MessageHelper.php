<?php

namespace App\Helper;

use App\Dto\Contract\ContractMessageDto;
use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductVariant;

class MessageHelper
{
    public static function renderProductMessage(Product $product): string
    {
        $name = $product->getName();

        $message = "ℹ️ Название: $name \n\n";

        $variants = $product->getVariants();

        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            $name = $variant->getName();
            $price = $variant->getPrice();
            $price = $price['price'];
            $count = $variant->getCount();

            $message .= "Вариант: $name \n";
            $message .= "Цена: $price \n";
            $message .= "Доступное количество: $count \n";
            $message .= "\n";
        }

        return $message;
    }

    public static function createContractMessage(
        string $message,
        ?string $photo = null,
        ?array $keyBoard = null,
    ): ContractMessageDto {
        $contractMessage = (new ContractMessageDto())
            ->setMessage($message)
        ;

        if ($photo) {
            $contractMessage->setPhoto($photo);
        }

        if ($keyBoard) {
            $contractMessage->setKeyBoard($keyBoard);
        }

        return $contractMessage;
    }
}
