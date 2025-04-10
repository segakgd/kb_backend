<?php

namespace App\Service\Constructor\Actions\Order;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Actions\AbstractAction;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class OrderContactsFullNameAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'order.contacts.full-name.action';
    }

    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getContent();

        $responsible->getCart()->setContacts(['fullName' => $content]);

        $message = "Очень приятно познакомиться $content. Укажите номер телефона для связи: ";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
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
