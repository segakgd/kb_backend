<?php

namespace App\Service\Constructor\Scheme;

use App\Dto\Scenario\ScenarioChainDto;
use App\Enum\TargetEnum;

class MainScheme
{
    public static function chain(TargetEnum $enum): ScenarioChainDto
    {
        return (new ScenarioChainDto())
            ->setTarget($enum->value)
            ->setRequirements([]);
    }
}