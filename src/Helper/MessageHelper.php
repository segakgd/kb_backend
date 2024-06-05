<?php

namespace App\Helper;

use App\Dto\Contract\ResponsibleMessageDto;
use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductVariant;

class MessageHelper
{
    public static function renderProductMessage(Product $product, int $now, int $total): string
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

        $message .= "\n";
        $message .= "Товар $now из $total";

        return $message;
    }

    public static function createResponsibleMessage(
        ?string $message = null,
        ?string $photo = null,
        ?array $keyBoard = null,
    ): ResponsibleMessageDto {
        $responsibleMessageDto = (new ResponsibleMessageDto());

        if ($message) {
            $responsibleMessageDto->setMessage($message);
        }

        if ($photo) {
            $responsibleMessageDto->setPhoto($photo);
        }

        if ($keyBoard) {
            $responsibleMessageDto->setKeyBoard($keyBoard);
        }

        return $responsibleMessageDto;
    }
}
