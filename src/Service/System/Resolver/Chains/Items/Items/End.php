<?php

namespace App\Service\System\Resolver\Chains\Items\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Contract;
use Exception;

class End // extends AbstractChain // todo удалить
{
    /**
     * @throws Exception
     */
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
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

        $contract->getResult()->addMessage($contractMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        return false;
    }
}
