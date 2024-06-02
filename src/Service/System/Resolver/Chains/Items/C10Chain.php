<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

class C10Chain extends AbstractChain
{
    public function success(ContractInterface $contract): ContractInterface
    {
        $content = $contract->getCacheDto()->getContent();

        $message = "Отличео! Это шаг 2 элемент цепочки C10. \n\n Вы кликнули на $content";

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
            keyBoard: [
                [
                    [
                        'text' => 'СИСТЕМНАЯ КНОПКА'
                    ],
                ],
            ]
        );

        $contract->getResult()->addMessage($contractMessage);

        return $contract;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Да 10'
                ],
                [
                    'text' => 'Нет 10'
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
            'Да 10',
            'Нет 10',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
