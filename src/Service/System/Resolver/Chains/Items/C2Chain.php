<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

class C2Chain extends AbstractChain
{
    public function success(ContractInterface $contract): ContractInterface
    {
        $content = $contract->getCacheDto()->getContent();

        $message = "Это шаг 1 элемент цепочки C2. \n\n Контент $content";

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
                    'text' => 'Да 2'
                ],
                [
                    'text' => 'Нет 2'
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
            'Да 2',
            'Нет 2',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
