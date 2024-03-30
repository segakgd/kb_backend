<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Chains\AbstractChain;
use App\Service\System\Resolver\Chains\Dto\Condition;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\Chains\Dto\Contract;
use App\Service\System\Resolver\Chains\Dto\ContractInterface;

class C1Chain extends AbstractChain
{
    public function validate(string $content): bool
    {
        return true;
    }

    public function condition(): ConditionInterface
    {
        return new Condition();
    }

    public function success(ContractInterface $contract, CacheDto $cacheDto): ContractInterface
    {
        $content = $cacheDto->getContent();

        $shipping = $cacheDto->getCart()->getShipping();

        $shipping['address']['apartment'] = $content;

        $cacheDto->getCart()->setShipping($shipping);

        $message = "Ваши апартаменты $content. \n\n Хотите что-то изменить?";

        $replyMarkups = [
            [
                [
                    'text' => 'Изменить контакты'
                ],
                [
                    'text' => 'Изменить доставку'
                ],
                [
                    'text' => 'Изменить продукты'
                ],
            ],
            [
                [
                    'text' => 'Продолжить',
                ],
            ],
            [
//                [
//                    'text' => 'Удалить заказ'
//                ],
//                [
//                    'text' => 'Оплатить'
//                ],
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $contractMessage = MessageHelper::createContractMessage(
            $message,
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);



        return new Contract(); // todo ....
    }

    public function fail(): ContractInterface
    {
        return new Contract();
    }
}
