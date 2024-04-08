<?php

namespace App\Service\System\Resolver\Dto;

use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\ChainStatusEnum;
use App\Enum\JumpEnum;
use App\Enum\VisitorEventStatusEnum;

interface ContractInterface
{
    public function getChain(): ?CacheChainDto;

    public function setChain(?CacheChainDto $chain): static;

    public function getNextCondition(): ?ConditionInterface;

    public function setNextCondition(?ConditionInterface $nextCondition): static;

    public function getResult(): ?ResultInterface;

    public function setResult(?ResultInterface $result): static;

    public function getCacheDto(): ?CacheDto;

    public function setCacheDto(?CacheDto $cacheDto): static;

    public function getCacheCart(): ?CacheCartDto;

    public function setCacheCart(?CacheCartDto $cacheCart): static;

    public function getJump(): ?JumpEnum;

    public function setJump(?JumpEnum $jump): static;

    public function getStatus(): ?VisitorEventStatusEnum;

    public function setStatus(?VisitorEventStatusEnum $status): static;

    public function getContent(): ?string;

    public function setContent(?string $content): static;
}
