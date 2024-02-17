<?php

namespace App\Dto\Scenario;

class WrapperScenarioDto
{
    private array $scenarios;

    public function getScenarios(): array
    {
        return $this->scenarios;
    }

    public function setScenarios(array $scenarios): static
    {
        $this->scenarios = $scenarios;

        return $this;
    }

    public function addScenarios(ScenarioDto $scenario): static
    {
        $this->scenarios[] = $scenario;

        return $this;
    }
}
