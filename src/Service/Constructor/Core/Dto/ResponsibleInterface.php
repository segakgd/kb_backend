<?php

namespace App\Service\Constructor\Core\Dto;

use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Enum\VisitorEventStatusEnum;

interface ResponsibleInterface
{
    public function getContent(): ?string;

    public function setContent(?string $content): static;

    public function getCart(): CacheCartDto;

    public function setCart(CacheCartDto $cart): static;

    public function getEvent(): ?CacheEventDto;

    public function setEvent(?CacheEventDto $event): static;

    public function clearEvent(): static;

    public function getChain(): ?CacheChainDto;

    public function setChain(?CacheChainDto $chain): static;

    public function getResult(): ?ResultInterface;

    public function setResult(?ResultInterface $result): static;

    public function getStatus(): ?VisitorEventStatusEnum;

    public function setStatus(?VisitorEventStatusEnum $status): static;

    public function getBot(): ?BotDto;

    public function setBot(?BotDto $bot): Responsible;
}
