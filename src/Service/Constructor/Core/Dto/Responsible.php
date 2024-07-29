<?php

namespace App\Service\Constructor\Core\Dto;

use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Enum\TargetEnum;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CacheHelper;

class Responsible implements ResponsibleInterface
{
    private ?string $content = null;

    private ?CacheCartDto $cart = null;

    private ?CacheEventDto $event = null;

    public ?CacheChainDto $chain = null;

    private ?ResultInterface $result = null;

    private ?TargetEnum $jump = null;

    private ?VisitorEventStatusEnum $status = VisitorEventStatusEnum::New;

    private ?BotDto $botDto = null;

    public function __construct()
    {
        if (is_null($this->result)) {
            $this->result = new Result();
        }

        if (is_null($this->cart)) {
            $this->cart = new CacheCartDto();
        }

        if (is_null($this->event)) {
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

    public function getChain(): ?CacheChainDto
    {
        return $this->chain;
    }

    public function setChain(?CacheChainDto $chain): static
    {
        $this->chain = $chain;

        return $this;
    }

    public function getResult(): ?ResultInterface
    {
        return $this->result;
    }

    public function setResult(?ResultInterface $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getJump(): ?TargetEnum
    {
        return $this->jump;
    }

    public function setJump(?TargetEnum $jump): static
    {
        $this->jump = $jump;

        return $this;
    }

    public function isExistJump(): bool
    {
        return !is_null($this->jump);
    }

    public function getStatus(): ?VisitorEventStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?VisitorEventStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getBotDto(): ?BotDto
    {
        return $this->botDto;
    }

    public function setBotDto(?BotDto $botDto): Responsible
    {
        $this->botDto = $botDto;

        return $this;
    }
}
