<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Chains\AbstractChain;
use App\Service\System\Resolver\Chains\Dto\Condition;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

class C1Chain extends AbstractChain
{
    public function success(ContractInterface $contract): ContractInterface
    {
        $content = $contract->getContent();

        $data = $contract->getData();
        $data['shipping']['address']['apartment'] = $content;

        $contract->setData($data);

        $message = "Ваши апартаменты $content. \n\n Хотите что-то изменить?";

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
            keyBoard: $contract->getNextCondition()->getKeyBoard()
        );

        $contract->addMessage($contractMessage);

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
