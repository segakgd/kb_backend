<?php

namespace App\Dto\Scenario;

class ActionAfterScenarioDto
{
    private ?string $event = null;

    private WaitingScenarioDto $waiting;
}
