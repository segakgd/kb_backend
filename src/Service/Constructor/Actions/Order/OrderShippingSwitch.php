<?php

namespace App\Service\Constructor\Actions\Order;

use App\Enum\TargetEnum;
use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class OrderShippingSwitch extends AbstractChain
{
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $message = 'Введите адрес доставки:';

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

    public function perform(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getContent();

        if ($content === 'Нет') {
            $responsible->setJump(TargetEnum::OrderFinishChain);

            return false;
        }

        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
