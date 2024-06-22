<?php

namespace App\Service\Constructor\Core\Dto;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\TargetEnum;
use App\Enum\VisitorEventStatusEnum;

class Responsible implements ResponsibleInterface
{
    public ?CacheChainDto $chain = null; // todo объеденить с $nextCondition

    private ?ResultInterface $result = null;

    private ?CacheDto $cacheDto;

    private ?TargetEnum $jump = null;

    private ?VisitorEventStatusEnum $status = VisitorEventStatusEnum::New;

    private bool $contractStatus = false; // todo точно нужен?

    private ?BotDto $botDto = null;

    public function __construct()
    {
        if (is_null($this->result)) {
            $this->result = new Result();
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

    public function isContractStatus(): bool
    {
        return $this->contractStatus;
    }

    public function setContractStatus(bool $contractStatus): static
    {
        $this->contractStatus = $contractStatus;

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
