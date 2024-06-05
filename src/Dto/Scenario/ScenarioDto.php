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

    private ?string $alias;

    #[Assert\Choice(self::AVAILABLE_TYPE)]
    private string $type;

    /** @var array<ScenarioContractDto> */
    private array $contracts = [];

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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): static
    {
        $this->alias = $alias;

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

    public function getContracts(): array
    {
        return $this->contracts;
    }

    public function setContracts(array $contracts): static
    {
        $this->contracts = $contracts;

        return $this;
    }

    public function addContract(ScenarioContractDto $scenarioContractDto): static
    {
        $this->contracts[] = $scenarioContractDto;

        return $this;
    }
}
