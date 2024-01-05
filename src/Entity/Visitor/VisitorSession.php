<?php

namespace App\Entity\Visitor;

use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/** Сессия пользователя */
#[ORM\Entity(repositoryClass: VisitorSessionRepository::class)]
class VisitorSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 30)]
    private ?string $channel = null;

    #[ORM\Column]
    private ?int $channelId = null;

    #[ORM\Column(nullable: true)]
    private ?int $visitorId = null;

    #[ORM\Column(nullable: true)]
    private ?int $visitorEvent = null;

    #[ORM\Column]
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

    public function getChannelId(): ?int
    {
        return $this->channelId;
    }

    public function setChannelId(int $channelId): static
    {
        $this->channelId = $channelId;

        return $this;
    }

    public function setVisitorId(int $visitorId): self
    {
        $this->visitorId = $visitorId;

        return $this;
    }

    public function getVisitorEvent(): ?int
    {
        return $this->visitorEvent;
    }

    public function setVisitorEvent(?int $visitorEvent): self
    {
        $this->visitorEvent = $visitorEvent;

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
