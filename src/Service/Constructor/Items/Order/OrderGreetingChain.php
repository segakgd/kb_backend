<?php

namespace App\Service\Constructor\Items\Order;

use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class OrderGreetingChain extends AbstractChain
{
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        // TODO: Implement complete() method.
    }

    public function perform(ResponsibleInterface $responsible): bool
    {
        // TODO: Implement perform() method.
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        // TODO: Implement validate() method.
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        // TODO: Implement condition() method.
    }
}
