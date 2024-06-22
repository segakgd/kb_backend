<?php

namespace App\Service\Constructor\Core\Dto;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\TargetEnum;
use App\Enum\VisitorEventStatusEnum;

interface ResponsibleInterface
{
    public function getChain(): ?CacheChainDto;

    public function setChain(?CacheChainDto $chain): static;

    public function getResult(): ?ResultInterface;

    public function setResult(?ResultInterface $result): static;

    public function getCacheDto(): ?CacheDto;

    public function setCacheDto(?CacheDto $cacheDto): static;

    public function getJump(): ?TargetEnum;

    public function setJump(?TargetEnum $jump): static;

    public function getStatus(): ?VisitorEventStatusEnum;

    public function setStatus(?VisitorEventStatusEnum $status): static;

    public function isContractStatus(): bool;

    public function setContractStatus(bool $contractStatus): static;

    public function getBotDto(): ?BotDto;

    public function setBotDto(?BotDto $botDto): Responsible;
}
