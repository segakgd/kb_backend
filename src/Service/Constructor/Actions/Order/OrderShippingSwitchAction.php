<?php

namespace App\Service\Constructor\Actions\Order;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Actions\AbstractAction;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class OrderShippingSwitchAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'order.shipping.switch';
    }

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

    public function before(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getContent();

        //        if ($content === 'Нет') {
        //            $responsible->setJumpToScenario(TargetEnum::OrderFinishAction);
        //
        //            return false;
        //        }

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
