<?php

namespace App\Service\Constructor\Scheme;

use App\Dto\Scenario\ScenarioDto;

interface SchemeInterface
{
    public static function scheme(): ScenarioDto;
}