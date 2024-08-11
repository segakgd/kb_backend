<?php

namespace App\Entity\Visitor;

use App\Enum\VisitorEventStatusEnum;
use App\Repository\Visitor\VisitorEventRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitorEventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 15)]
    private string $status = 'new';

    #[ORM\Column(nullable: true)]
    private ?int $projectId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $error = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 36)]
    private ?string $scenarioUUID = null;

    // todo переделать под работу с дто
    #[ORM\Column]
    private array $responsible = [];

    #[ORM\Column]
    private ?int $sessionId = null;

    public function __construct()
    {
        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?VisitorEventStatusEnum
    {
        return VisitorEventStatusEnum::from($this->status);
    }

    public function setStatus(VisitorEventStatusEnum $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    public function isStatusAvailableForHandle(): bool
    {
        return VisitorEventStatusEnum::New === $this->getStatus()
        || VisitorEventStatusEnum::Repeat === $this->getStatus();
    }

    public function isRepeatStatuses(): bool
    {
        return VisitorEventStatusEnum::Repeat === $this->getStatus();
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(?int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): static
    {
        $this->error = $error;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getScenarioUUID(): ?string
    {
        return $this->scenarioUUID;
    }

    public function setScenarioUUID(string $scenarioUUID): static
    {
        $this->scenarioUUID = $scenarioUUID;

        return $this;
    }

    public function getResponsible(): array
    {
        return $this->responsible;
    }

    public function setResponsible(array $responsible): static
    {
        $this->responsible = $responsible;

        return $this;
    }

    public function getSessionId(): ?int
    {
        return $this->sessionId;
    }

    public function setSessionId(int $sessionId): static
    {
        $this->sessionId = $sessionId;

        return $this;
    }
}
