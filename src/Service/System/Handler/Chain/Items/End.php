<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\ChainsEnum;
use App\Helper\MessageHelper;
use App\Service\System\Contract;
use Exception;

class End extends AbstractChain
{
    /**
     * @throws Exception
     */
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        $goto = match ($content) {
            'вернуться к товарам' => ChainsEnum::ShowShopProductsCategory->value,
            'вернуться к категориям' => ChainsEnum::ShopProducts->value,
            'вернуться в главное меню' => 'main',
            'в корзину' => 'cart',
            default => null
        };

        if (is_null($goto)) {
            throw new Exception('что-то случилось. нету goto');
        }

        $contract->setGoto($goto);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы... мб вам...',
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        $available = [
            'вернуться к товарам',
            'вернуться к категориям',
            'вернуться в главное меню',
            'в корзину',
        ];

        if (in_array($content, $available)) {
            return true;
        }

        return false;
    }
}
