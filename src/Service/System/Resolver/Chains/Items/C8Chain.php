<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Chains\AbstractChain;
use App\Service\System\Resolver\Chains\Dto\Condition;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\ContractInterface;

class C8Chain extends AbstractChain
{
    public function success(
        ContractInterface $contract,
        ConditionInterface $nextCondition,
        string $content
    ): ContractInterface {
        $message = "Вы кликнули на $content";

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
            keyBoard: $nextCondition->getKeyBoard()
        );

        $contract->addMessage($contractMessage);

        return $contract;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Да'
                ],
                [
                    'text' => 'Нет'
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }

    public function validate(string $content): bool
    {
        $validData = [
            'Да',
            'Нет',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
