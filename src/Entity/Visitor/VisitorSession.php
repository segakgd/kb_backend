<?php

namespace App\Entity\Visitor;

use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitorSessionRepository::class)]
class VisitorSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $visitorId = null;

    #[ORM\Column(nullable: true)]
    private ?int $chatEvent = null;

    private ?DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        if ($this->createdAt === null){
            $this->createdAt = new DateTimeImmutable();
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVisitorId(): int
    {
        return $this->visitorId;
    }

    public function setVisitorId(int $visitorId): self
    {
        $this->visitorId = $visitorId;

        return $this;
    }

    public function getChatEvent(): ?int
    {
        return $this->chatEvent;
    }

    public function setChatEvent(?int $chatEvent): self
    {
        $this->chatEvent = $chatEvent;

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
}
