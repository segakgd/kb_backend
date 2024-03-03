<?php

namespace App\Dto\SessionCache\Cache;

class CacheDto
{
    private ?string $eventUUID = null;

    private ?CacheCartDto $cart = null;

    private array $event = [];

    private ?string $content = null;

    public function __construct()
    {
        if (!$this->cart) {
            $this->cart = new CacheCartDto;
        }
    }

    public function getEventUUID(): ?string
    {
        return $this->eventUUID;
    }

    public function setEventUUID(?string $eventUUID): static
    {
        $this->eventUUID = $eventUUID;

        return $this;
    }

    public function getCart(): CacheCartDto
    {
        return $this->cart;
    }

    public function setCart(CacheCartDto $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getEvent(): array
    {
        return $this->event;
    }

    public function setEvent(array $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function addEvent(CacheEventDto $cacheEventDto): static
    {
        $this->event[] = $cacheEventDto;

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
}
