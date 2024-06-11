<?php

namespace App\Service\System\Constructor\Scheme;

use App\Dto\Scenario\ScenarioChainDto;
use App\Enum\JumpEnum;

class MainScheme
{
    public static function chain(JumpEnum $enum): ScenarioChainDto
    {
        return (new ScenarioChainDto())
            ->setTarget($enum->value)
            ->setRequirements([]);
    }
}