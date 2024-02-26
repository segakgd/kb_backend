<?php

namespace App\Service\System\Handler\Dto;

class CacheDto
{
    private ?int $eventPrev = null;

    private array $cart = [];

    private array $event = [];

    private string $content;

    public function getEventPrev(): ?int
    {
        return $this->eventPrev;
    }

    public function setEventPrev(?int $eventPrev): void
    {
        $this->eventPrev = $eventPrev;
    }

    public function getCart(): array
    {
        return $this->cart;
    }

    public function setCart(array $cart): void
    {
        $this->cart = $cart;
    }

    public function getEvent(): array
    {
        return $this->event;
    }

    public function setEvent(array $event): void
    {
        $this->event = $event;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
