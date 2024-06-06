<?php

namespace App\Entity\Scenario;

use App\Doctrine\Types\ScenarioContractDtoArrayType;
use App\Dto\Scenario\ScenarioContractDto;
use App\Repository\Scenario\ScenarioRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScenarioRepository::class)]
class Scenario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36)]
    private ?string $UUID = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 25)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $projectId = null;

    #[ORM\Column(nullable: true)]
    private ?int $botId = null;

    #[ORM\Column(type: ScenarioContractDtoArrayType::TYPE_NAME, nullable: true)]
    private ?ScenarioContractDto $contract = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    #[ORM\Column(length: 100)]
    private ?string $alias = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUUID(): ?string
    {
        return $this->UUID;
    }

    public function setUUID(string $UUID): static
    {
        $this->UUID = $UUID;

        return $this;
    }

    public function getContract(): ScenarioContractDto
    {
        return $this->contract;
    }

    public function setContract(ScenarioContractDto $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getBotId(): ?int
    {
        return $this->botId;
    }

    public function setBotId(?int $botId): static
    {
        $this->botId = $botId;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function markAtDeleted(): static
    {
        $this->deletedAt = new DateTimeImmutable();

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }
}
