<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Chains\AbstractChain;
use App\Service\System\Resolver\Chains\Dto\Condition;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\Chains\Dto\ContractInterface;

class C2Chain extends AbstractChain
{
    public function success(ContractInterface $contract, string $content): ContractInterface
    {
        $message = "Ваши апартаменты $content. \n\n Хотите что-то изменить?";

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
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
