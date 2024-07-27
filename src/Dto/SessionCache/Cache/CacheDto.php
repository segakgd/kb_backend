<?php

namespace App\Dto\SessionCache\Cache;

use App\Dto\Common\AbstractDto;
use App\Helper\CacheHelper;

class CacheDto extends AbstractDto
{
    private ?string $content = null;

    private ?CacheCartDto $cart = null;

    private ?CacheEventDto $event = null;

    public function __construct()
    {
        if (!$this->cart) {
            $this->cart = new CacheCartDto();
        }

        if (!$this->event) {
            $this->event = new CacheEventDto();
        }
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

    public function getCart(): CacheCartDto
    {
        return $this->cart;
    }

    public function setCart(CacheCartDto $cart): static
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
        $this->event = $event;

        return $this;
    }

    public function clearEvent(): static
    {
        $this->event = CacheHelper::createCacheEventDto();

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $cacheDto = new static();

        if (isset($data['cart'])) {
            $cacheDto->cart = CacheCartDto::fromArray($data['cart']);
        }

        if (isset($data['event'])) {
            $cacheDto->event = CacheEventDto::fromArray($data['event']);
        }

        if (isset($data['content'])) {
            $cacheDto->content = $data['content'];
        }

        return $cacheDto;
    }

    public function toArray(): array
    {
        $data = [];

        $data['cart'] = $this->cart->toArray();
        $data['event'] = $this->event->toArray();
        $data['content'] = $this->content;

        return $data;
    }
}
