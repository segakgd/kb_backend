<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\ChainsEnum;
use App\Helper\MessageHelper;
use App\Service\System\Contract;
use Exception;

class End
{
    /**
     * @throws Exception
     */
    public function handle(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        if ($this->checkSystemCondition($content)) {
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


    private function checkSystemCondition(string $content): bool
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
