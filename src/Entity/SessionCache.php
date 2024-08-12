<?php

namespace App\Entity;

use App\Doctrine\Types\Session\SessionCartDtoArrayType;
use App\Doctrine\Types\Session\SessionEventDtoArrayType;
use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Repository\SessionCacheRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionCacheRepository::class)]
class SessionCache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: SessionCartDtoArrayType::TYPE_NAME, nullable: true)]
    private ?CacheCartDto $cart = null;

    #[ORM\Column(type: SessionEventDtoArrayType::TYPE_NAME, nullable: true)]
    private ?CacheEventDto $event = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $content = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        if ($this->cart === null) {
            $this->cart = new CacheCartDto();
        }

        if ($this->event === null) {
            $this->event = new CacheEventDto();
        }

        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable();
        }

        if ($this->updatedAt === null) {
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?CacheCartDto
    {
        return $this->cart;
    }

    public function setCart(?CacheCartDto $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getEvent(): ?CacheEventDto
    {
        return $this->event;
    }

    public function setEvent(?CacheEventDto $event): static
    {
        $this->event = clone $event;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

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

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
