<?php

namespace App\Controller\Admin\Scenario\DTO\Response;

class ScenarioRespDto
{
    private readonly ?int $id;

    private readonly array $scenario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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
