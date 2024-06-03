<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

class C5Chain extends AbstractChain
{
    public function success(ContractInterface $contract): ContractInterface
    {
        $content = $contract->getCacheDto()->getContent();

        $message = "Это шаг 1 элемент цепочки C5. \n\n Вы кликнули на $content";

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
            keyBoard: $contract->getNextCondition()?->getKeyBoard()
        );

        $contract->getResult()->addMessage($contractMessage);

        return $contract;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Да 5'
                ],
                [
                    'text' => 'Нет 5'
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }

    public function validate(ContractInterface $contract): bool
    {
        $content = $contract->getCacheDto()->getContent();

        $validData = [
            'Да 5',
            'Нет 5',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
