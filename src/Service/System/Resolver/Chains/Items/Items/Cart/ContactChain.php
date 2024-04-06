<?php

namespace App\Service\System\Resolver\Chains\Items\Items\Cart;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Handler\Chain\AbstractChain;
use App\Service\System\Resolver\Dto\Contract;

class ContactChain extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();
        $contacts = $cacheDto->getCart()->getContacts();

        $contacts['full'] = $content;

        $cacheDto->getCart()->setContacts($contacts);

        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $contractMessage = MessageHelper::createContractMessage(
            "Отлично, $content а теперь пришли мне свой номер телефона",
            null,
            $replyMarkups,
        );

        $contract->getResult()->addMessage($contractMessage);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        // todo проверить на стрингу? оО и мат

        return true;
    }
}
