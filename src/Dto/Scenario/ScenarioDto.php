<?php

namespace App\Dto\Scenario;

use Symfony\Component\Validator\Constraints as Assert;

class ScenarioDto
{
    private const AVAILABLE_TYPE = [
        'message',
        'command',
    ];

    private string $UUID;

    private ?string $ownerUUID = null;

    private string $name;

    #[Assert\Choice(self::AVAILABLE_TYPE)]
    private string $type;

    private ?string $description = null;

    /** @var array<ScenarioStepDto> */
    private array $steps = [];

    public function getUUID(): string
    {
        return $this->UUID;
    }

    public function setUUID(string $UUID): static
    {
        $this->UUID = $UUID;

        return $this;
    }

    public function getOwnerUUID(): ?string
    {
        return $this->ownerUUID;
    }

    public function setOwnerUUID(?string $ownerUUID): static
    {
        $this->ownerUUID = $ownerUUID;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function addSteps(ScenarioStepDto $step): static // addSteps -> addStep
    {
        $this->steps[] = $step;

        return $this;
    }
}
