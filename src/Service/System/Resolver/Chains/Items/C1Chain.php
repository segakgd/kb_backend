<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

class C1Chain extends AbstractChain
{
    public function success(ContractInterface $contract): ContractInterface
    {
        $content = $contract->getCacheDto()->getContent();

        $message = "Вы тукнули на $content. \n\n Мб тыкнуть на что-то другое?";

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
            keyBoard: $contract->getNextCondition()->getKeyBoard()
        );

        $contract->getResult()->addMessage($contractMessage);

        return $contract;
    }

    public function condition(): ConditionInterface
    {
        return new Condition();
    }

    public function validate(ContractInterface $contract): bool
    {
        return true;
    }
}
