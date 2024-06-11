<?php

namespace App\Service\System\Constructor\Core\Dto;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\JumpEnum;
use App\Enum\VisitorEventStatusEnum;

interface ResponsibleInterface
{
    public function getChain(): ?CacheChainDto;

    public function setChain(?CacheChainDto $chain): static;

    public function getNextCondition(): ?ConditionInterface;

    public function setNextCondition(?ConditionInterface $nextCondition): static;

    public function getResult(): ?ResultInterface;

    public function setResult(?ResultInterface $result): static;

    public function getCacheDto(): ?CacheDto;

    public function setCacheDto(?CacheDto $cacheDto): static;

    public function getJump(): ?JumpEnum;

    public function setJump(?JumpEnum $jump): static;

    public function getStatus(): ?VisitorEventStatusEnum;

    public function setStatus(?VisitorEventStatusEnum $status): static;

    public function isContractStatus(): bool;

    public function setContractStatus(bool $contractStatus): static;

    public function getBotDto(): ?BotDto;

    public function setBotDto(?BotDto $botDto): Responsible;
}
