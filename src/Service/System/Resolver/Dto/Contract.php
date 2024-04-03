<?php

namespace App\Service\System\Resolver\Dto;

use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Enum\ChainStatusEnum;
use App\Enum\JumpEnum;

class Contract implements ContractInterface
{
    public ?CacheChainDto $chain = null;

    private ?ConditionInterface $nextCondition = null;

    private ?ResultInterface $result = null;

    private ?CacheCartDto $cacheCart = null;

    private ?JumpEnum $jump = null;

    private ?ChainStatusEnum $status = null;

    private ?string $content = null;

    public function __construct()
    {
        if (is_null($this->result)) {
            $this->result = new Result;
        }
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

    public function getNextCondition(): ?ConditionInterface
    {
        return $this->nextCondition;
    }

    public function setNextCondition(?ConditionInterface $nextCondition): static
    {
        $this->nextCondition = $nextCondition;

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

    public function getCacheCart(): ?CacheCartDto
    {
        return $this->cacheCart;
    }

    public function setCacheCart(?CacheCartDto $cacheCart): static
    {
        $this->cacheCart = $cacheCart;

        return $this;
    }

    public function getJump(): ?JumpEnum
    {
        return $this->jump;
    }

    public function setJump(?JumpEnum $jump): static
    {
        $this->jump = $jump;

        return $this;
    }

    public function getStatus(): ?ChainStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?ChainStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
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
}
