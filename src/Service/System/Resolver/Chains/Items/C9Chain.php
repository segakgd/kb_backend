<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

class C9Chain extends AbstractChain
{
    public function success(ContractInterface $contract): ContractInterface
    {
        $content = $contract->getCacheDto()->getContent();

        $message = "Это шаг 2 элемент цепочки C9. \n\n Вы кликнули на $content";

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
            keyBoard: $contract->getNextCondition()->getKeyBoard()
        );

        $contract->getResult()->addMessage($contractMessage);

        return $contract;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Да 9'
                ],
                [
                    'text' => 'Нет 9'
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
            'Да 9',
            'Нет 9',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
