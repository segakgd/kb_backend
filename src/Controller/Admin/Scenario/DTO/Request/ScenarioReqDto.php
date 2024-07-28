<?php

namespace App\Controller\Admin\Scenario\DTO\Request;

readonly class ScenarioReqDto
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
