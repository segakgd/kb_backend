<?php

namespace App\Controller\Admin\ScenarioTemplate\Request;

readonly class ScenarioTemplateUpdateRequest
{
    private int $id;

    private string $name;

    // todo реализовать private readonly array $scenario;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
