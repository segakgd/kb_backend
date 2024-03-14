<?php

namespace App\Dto\SessionCache\Cache;

use App\Enum\ChainsEnum;

class CacheChainDto
{
    private ChainsEnum $target;

    private bool $finished;

    private bool $repeat;

    public function getTarget(): ChainsEnum
    {
        return $this->target;
    }

    public function setTarget(ChainsEnum $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function isNotFinished(): bool
    {
        return !$this->finished;
    }

    public function setFinished(bool $finished): static
    {
        $this->finished = $finished;

        return $this;
    }

    public function isRepeat(): bool
    {
        return $this->repeat;
    }

    public function setRepeat(bool $repeat): CacheChainDto
    {
        $this->repeat = $repeat;
        return $this;
    }
}
