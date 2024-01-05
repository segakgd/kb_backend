<?php

namespace App\Dto\Scenario;

class ScenarioDto
{
    private string $name;

    private string $type;

    private ScenarioContextDto $content;

    private array $actionAfter;

    private array $actionBefore;

    private array $sub;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getContent(): ScenarioContextDto
    {
        return $this->content;
    }

    public function setContent(ScenarioContextDto $content): void
    {
        $this->content = $content;
    }

    public function getActionAfter(): array
    {
        return $this->actionAfter;
    }

    public function addActionAfter(ScenarioActionDto $actionAfter): void
    {
        $this->actionAfter[] = $actionAfter;
    }

    public function getActionBefore(): array
    {
        return $this->actionBefore;
    }

    public function addActionBefore(ScenarioActionDto $actionBefore): void
    {
        $this->actionBefore[] = $actionBefore;
    }

    public function getSub(): array
    {
        return $this->sub;
    }

    public function addSub(ScenarioDto $sub): void
    {
        $this->sub[] = $sub;
    }
}
