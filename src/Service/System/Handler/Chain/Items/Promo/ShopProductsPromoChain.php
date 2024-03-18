<?php

namespace App\Service\System\Handler\Chain\Items\Promo;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;

class ShopProductsPromoChain extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $contractMessage = MessageHelper::createContractMessage(
            'Выводим первый товар',
        );

        $contract->addMessage($contractMessage);

        return false;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $replyMarkups = KeyboardHelper::getProductNav();

        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.',
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
