<?php

namespace App\Controller\Admin\ScenarioTemplate\Request;

readonly class ScenarioTemplateRequest
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
