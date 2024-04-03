<?php

namespace App\Entity\Visitor;

use App\Repository\Visitor\VisitorEventRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitorEventRepository::class)]
class VisitorEvent
{
    public const STATUS_IN_PROCESS = 'in_process';

    public const STATUS_NEW = 'new';

    public const STATUS_AWAIT = 'await';

    public const STATUS_DONE = 'done';

    public const STATUS_FAIL = 'fail';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 15)]
    private string $status = self::STATUS_NEW;

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
    private array $contract = [];

    public function __construct()
    {
        if ($this->createdAt === null){
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
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

    public function getContract(): array
    {
        return $this->contract;
    }

    public function setContract(array $contract): static
    {
        $this->contract = $contract;

        return $this;
    }
}
