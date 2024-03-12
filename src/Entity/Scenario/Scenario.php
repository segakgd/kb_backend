<?php

namespace App\Entity\Scenario;

use App\Dto\Scenario\ScenarioStepDto;
use App\Repository\Scenario\ScenarioRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
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

    /** @deprecated */
    #[ORM\Column(length: 36, nullable: true)]
    private ?string $ownerUUID = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 25)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $projectId = null;

    #[ORM\Column(nullable: true)]
    private ?int $botId = null;

    /** @var array<ScenarioStepDto> */
    #[ORM\Column(type: Types::JSON)]
    private array $steps = [];

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

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

    /** @deprecated  */
    public function getOwnerUUID(): ?string
    {
        return $this->ownerUUID;
    }

    /** @deprecated  */
    public function setOwnerUUID(?string $ownerUUID): static
    {
        $this->ownerUUID = $ownerUUID;

        return $this;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function setSteps(array $steps): static
    {
        $this->steps = $steps;

        return $this;
    }

    public function addStep(ScenarioStepDto $step): static
    {
        $this->steps[] = $step;

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
}
