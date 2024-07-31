<?php

namespace App\Service\Constructor\Actions\Ecommerce;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Chains\AbstractAction;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class StartAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'start.chain';
    }

    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $message = 'Выберите интересующую вас категорию товаров: ';

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
