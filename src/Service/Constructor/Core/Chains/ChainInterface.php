<?php

namespace App\Service\Constructor\Core\Chains;

use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

interface ChainInterface
{
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface;

    public function perform(ResponsibleInterface $responsible): bool;

    public function validate(ResponsibleInterface $responsible): bool;

    public function condition(ResponsibleInterface $responsible): ConditionInterface;

    public function fail(ResponsibleInterface $responsible): ResponsibleInterface;
}
