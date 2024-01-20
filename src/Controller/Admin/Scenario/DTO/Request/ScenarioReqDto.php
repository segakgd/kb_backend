<?php

namespace App\Controller\Admin\Scenario\DTO\Request;

class ScenarioReqDto
{
    private readonly string $name;

    private readonly array $scenario;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getScenario(): array
    {
        return $this->scenario;
    }

    public function setScenario(array $scenario): void
    {
        $this->scenario = $scenario;
    }
}
