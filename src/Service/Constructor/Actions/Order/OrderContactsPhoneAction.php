<?php

namespace App\Service\Constructor\Actions\Order;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Actions\AbstractAction;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class OrderContactsPhoneAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'order.contacts.phone.chain';
    }

    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getContent();

        $responsible->getCart()->setContacts(['phone' => $content]);

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

    public function before(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function after(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
