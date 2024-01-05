<?php

namespace App\Dto\Scenario;

class ScenarioActionDto
{
    private string $type;

    private array $value;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function setValue(array $value): void
    {
        $this->value = $value;
    }
}
