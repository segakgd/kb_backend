<?php

namespace App\Service\System\Handler\Chain\Items\Cart;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Handler\Chain\AbstractChain;
use App\Service\System\Resolver\Dto\Contract;

class ContactViewChain extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $contractMessage = MessageHelper::createContractMessage(
            'Отлично, давай начнём оформление заказа, пришли мне свое ФИО.',
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
