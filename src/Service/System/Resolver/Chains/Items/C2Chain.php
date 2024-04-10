<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Enum\JumpEnum;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

class C2Chain extends AbstractChain
{
    public function success(ContractInterface $contract): ContractInterface
    {
        $content = $contract->getCacheDto()->getContent();

        if ($content === 'На на шаг 4') {
            $contract->setJump(JumpEnum::refChain4);

            return $contract;
        }

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
                    'text' => 'На очко себе тыкни'
                ],
                [
                    'text' => 'На на шаг 4'
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

    public function validate(ContractInterface $contract): bool
    {
        $content = $contract->getCacheDto()->getContent();

        $validData = [
            'На очко себе тыкни',
            'На на шаг 4',
            'Нет',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
