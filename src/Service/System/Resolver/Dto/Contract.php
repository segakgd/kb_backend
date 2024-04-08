<?php

namespace App\Service\System\Resolver\Dto;

use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\JumpEnum;
use App\Enum\VisitorEventStatusEnum;

class Contract implements ContractInterface
{
    public ?CacheChainDto $chain = null;

    private ?ConditionInterface $nextCondition = null;

    private ?ResultInterface $result = null;

    /** @deprecated */
    private ?CacheCartDto $cacheCart = null;

    private ?CacheDto $cacheDto;

    private ?JumpEnum $jump = null;

    private ?VisitorEventStatusEnum $status = VisitorEventStatusEnum::New;

    private bool $stepsStatus = false;

    /** @deprecated */
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

    public function getCacheDto(): ?CacheDto
    {
        return $this->cacheDto;
    }

    public function setCacheDto(?CacheDto $cacheDto): static
    {
        $this->cacheDto = $cacheDto;

        return $this;
    }

    /** @deprecated */
    public function getCacheCart(): ?CacheCartDto
    {
        return $this->cacheCart;
    }

    /** @deprecated */
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

    public function getStatus(): ?VisitorEventStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?VisitorEventStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    /** @deprecated */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /** @deprecated */
    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isStepsStatus(): bool
    {
        return $this->stepsStatus;
    }

    public function setStepsStatus(bool $stepsStatus): static
    {
        $this->stepsStatus = $stepsStatus;

        return $this;
    }
}
