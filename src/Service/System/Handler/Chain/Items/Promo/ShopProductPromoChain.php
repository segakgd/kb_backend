<?php

namespace App\Service\System\Handler\Chain\Items\Promo;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;

class ShopProductPromoChain extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        // TODO: Implement success() method.
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
        // TODO: Implement validateCondition() method.
    }
}
