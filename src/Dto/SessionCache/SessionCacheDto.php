<?php

namespace App\Dto\SessionCache;

class SessionCacheDto
{
    private SessionCacheCartDto $cart;

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
