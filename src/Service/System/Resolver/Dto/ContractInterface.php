<?php

namespace App\Service\System\Resolver\Dto;

use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Enum\ChainStatusEnum;
use App\Enum\JumpEnum;

interface ContractInterface
{
    public function getChain(): ?CacheChainDto;

    public function setChain(?CacheChainDto $chain): static;

    public function getNextCondition(): ?ConditionInterface;

    public function setNextCondition(?ConditionInterface $nextCondition): static;

    public function getResult(): ?Result;

    public function setResult(?Result $result): static;

    public function getCacheCart(): ?CacheCartDto;

    public function setCacheCart(?CacheCartDto $cacheCart): static;

    public function getJump(): ?JumpEnum;

    public function setJump(?JumpEnum $jump): static;

    public function getStatus(): ?ChainStatusEnum;

    public function setStatus(?ChainStatusEnum $status): static;

    public function getContent(): ?string;

    public function setContent(?string $content): static;
}
