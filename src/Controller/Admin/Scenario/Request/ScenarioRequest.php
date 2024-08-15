<?php

namespace App\Controller\Admin\Scenario\Request;

readonly class ScenarioRequest
{
    private string $name;

    // todo реализовать private readonly array $scenario

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
