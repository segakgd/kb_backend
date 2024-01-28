<?php

namespace App\Dto\Scenario;

class ScenarioDto
{
    private string $name;

    private string $type;

    private WaitingScenarioDto $waiting;

    private ContentScenarioDto $content;

    private ActionAfterScenarioDto $actionAfter;

    private ActionBeforeScenarioDto $actionBefore;

    private ScenarioDto $sub;
}
