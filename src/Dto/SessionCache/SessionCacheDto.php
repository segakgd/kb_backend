<?php

namespace App\Dto\SessionCache;

class SessionCacheDto
{
    private ?string $eventPrev = null;

    private SessionCacheCartDto $cart;

    public function getEventPrev(): ?string
    {
        return $this->eventPrev;
    }

    public function setEventPrev(?string $eventPrev): static
    {
        $this->eventPrev = $eventPrev;

        return $this;
    }

    public function getCart(): SessionCacheCartDto
    {
        return $this->cart;
    }

    public function setCart(SessionCacheCartDto $cart): static
    {
        $this->cart = $cart;

        return $this;
    }
}
