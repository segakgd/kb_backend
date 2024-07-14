<?php

namespace App\Service\Constructor\Items\Order;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class OrderContactsPhoneChain extends AbstractChain
{
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

        $responsible->getCacheDto()->getCart()->setContacts(['phone' => $content]);

        $message = "Ваш номер $content. Вам нужна доставка?";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            keyBoard: [
                [
                    [
                        'text' => 'Да',
                    ],
                    [
                        'text' => 'Нет',
                    ],
                ],
            ]
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        return $this->makeCondition();
    }

    public function perform(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
