<?php

namespace App\Service\Common;

use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Entity\Ecommerce\Product;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Dto\Responsible;

readonly class PaginateService
{
    public function pug(Responsible $responsible, array $products, CacheDataDto $cacheDataDto): bool
    {
        /** @var Product $product */
        $product = $products['items'][0];

        $responsibleMessage = MessageHelper::createResponsibleMessage('');

        $cacheDataDto->setPageNow($products['paginate']['now']);
        $cacheDataDto->setProductId($product->getId());

        $message = MessageHelper::renderProductMessage(
            $product,
            $products['paginate']['now'],
            $products['paginate']['total'],
        );

        $responsibleMessage->setMessage($message);
        $responsibleMessage->setPhoto($product->getMainImage());

        $replyMarkups = KeyboardHelper::getProductNav();

        $responsibleMessage->setKeyBoard($replyMarkups);

        $responsible->getResult()->setMessage($responsibleMessage);

        return false;
    }
}
