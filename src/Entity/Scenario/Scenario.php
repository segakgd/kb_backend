<?php

namespace App\Entity\Scenario;

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

    #[ORM\Column(length: 25)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private array $content = [];

    #[ORM\Column(nullable: true)]
    private ?int $ownerStepId = null;

    #[ORM\Column(nullable: true)]
    private ?array $actionAfter = [];

    #[ORM\Column]
    private ?int $projectId = null;

    #[ORM\Column(length: 50)]
    private ?string $groupType = null;

    #[ORM\Column(nullable: true)]
    private ?int $botId = null;

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

    public function getOwnerStepId(): ?int
    {
        return $this->ownerStepId;
    }

    public function setOwnerStepId(int $ownerStepId): self
    {
        $this->ownerStepId = $ownerStepId;

        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getActionAfter(): ?array
    {
        return $this->actionAfter;
    }

    public function setActionAfter(?array $actionAfter): self
    {
        $this->actionAfter = $actionAfter;

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

    public function getGroupType(): ?string
    {
        return $this->groupType;
    }

    public function setGroupType(string $groupType): static
    {
        $this->groupType = $groupType;

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
