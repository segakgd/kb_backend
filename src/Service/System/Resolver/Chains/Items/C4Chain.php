<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Enum\JumpEnum;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

class C4Chain extends AbstractChain
{
    public function success(ContractInterface $contract): ContractInterface
    {
        $content = $contract->getCacheDto()->getContent();

        $message = "Это шаг 1 элемент цепочки C4. \n\n Вы кликнули на $content";

//        if ($content === 'Да') {
//            $contract->setJump(JumpEnum::refChain1);
//
//            return $contract;
//        }

        $message = "Вы кликнули на $content";

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
                    'text' => 'Да 4'
                ],
                [
                    'text' => 'Нет 4'
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
            'Да 4',
            'Нет 4',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
